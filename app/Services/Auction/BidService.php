<?php

namespace App\Services\Auction;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Redis\Connections\Connection as RedisConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * Auction bid servisi.
 *
 * Akış:
 *   1. Redis'te auction:{id} HASH'i üzerinden atomik bir Lua script çalıştırılır.
 *      Script şu kontrolleri tek adımda yapar:
 *        - Auction aktif mi (status + start/end zamanı)
 *        - Teklif tutarı >= current_price + min_increment mi
 *        - Rate-limit: customer başına 5/60s (Redis INCR+EXPIRE)
 *      Kabul ederse:
 *        - HSET current_price
 *        - ZADD auction:{id}:leaderboard (score=amount, member=customerId)
 *        - PUBLISH auction:{id}:bids (JSON payload)
 *   2. Redis'te kabul edilen tekliflerden sonra MySQL transaction:
 *        - bids insert
 *        - auctions.current_price update
 *
 * Redis-first tasarım: MySQL yazımı başarısız olsa bile current_price tutarlı kalır
 * (Redis hot-state tek doğruluk kaynağıdır; MySQL append-only ledger + yedek).
 */
class BidService
{
    /**
     * Atomik bid placement Lua script'i.
     *
     * KEYS[1] = auction:{id}            (HASH: status, end_at_ts, current_price, min_increment)
     * KEYS[2] = auction:{id}:leaderboard (ZSET)
     * KEYS[3] = auction:{id}:bids        (PUBSUB channel)
     * KEYS[4] = auction:bid:rl:{customer_id} (STRING, rate-limit sayacı)
     *
     * ARGV[1] = customer_id
     * ARGV[2] = amount (float string)
     * ARGV[3] = now (unix ts)
     * ARGV[4] = rate_limit_max (int, örn 5)
     * ARGV[5] = rate_limit_ttl (int saniye, örn 60)
     * ARGV[6] = payload JSON (broadcast için)
     *
     * Dönüş tablosu:
     *   {status, new_price, reason?}
     *   status: "ok" | "rate_limited" | "not_active" | "expired" | "too_low" | "not_found"
     */
    protected const LUA_PLACE_BID = <<<'LUA'
if redis.call('EXISTS', KEYS[1]) == 0 then
    return {'not_found', '0'}
end

local status = redis.call('HGET', KEYS[1], 'status')
if status ~= 'active' then
    return {'not_active', '0'}
end

local end_at_ts = tonumber(redis.call('HGET', KEYS[1], 'end_at_ts') or '0')
local now_ts = tonumber(ARGV[3])
if end_at_ts > 0 and now_ts > end_at_ts then
    return {'expired', '0'}
end

local rl_count = redis.call('INCR', KEYS[4])
if rl_count == 1 then
    redis.call('EXPIRE', KEYS[4], tonumber(ARGV[5]))
end
if rl_count > tonumber(ARGV[4]) then
    return {'rate_limited', '0'}
end

local current = tonumber(redis.call('HGET', KEYS[1], 'current_price') or '0')
local min_inc = tonumber(redis.call('HGET', KEYS[1], 'min_increment') or '0')
local amount = tonumber(ARGV[2])
if amount < (current + min_inc) then
    return {'too_low', tostring(current)}
end

redis.call('HSET', KEYS[1], 'current_price', tostring(amount))
redis.call('HSET', KEYS[1], 'top_bidder_id', ARGV[1])
redis.call('ZADD', KEYS[2], tostring(amount), ARGV[1])
redis.call('PUBLISH', KEYS[3], ARGV[6])

return {'ok', tostring(amount)}
LUA;

    protected function conn(): RedisConnection
    {
        return Redis::connection('auction');
    }

    /**
     * @return array{status:string,new_price:float,reason?:string}
     */
    public function place(Auction $auction, int $customerId, float $amount): array
    {
        $now = time();

        $payload = json_encode([
            'type'        => 'bid',
            'auction_id'  => $auction->id,
            'customer_id' => $customerId,
            'amount'      => $amount,
            'ts'          => $now,
        ], JSON_UNESCAPED_UNICODE);

        $keys = [
            "auction:{$auction->id}",
            "auction:{$auction->id}:leaderboard",
            "auction:{$auction->id}:bids",
            "auction:bid:rl:{$customerId}",
        ];

        $args = [
            (string) $customerId,
            (string) $amount,
            (string) $now,
            '5',
            '60',
            $payload,
        ];

        /**
         * phpredis eval: keys_count + keys + args.
         * Prefix'siz 'auction' bağlantısını kullanarak Go WS ile aynı key space'te kalıyoruz.
         */
        $result = $this->conn()->eval(self::LUA_PLACE_BID, count($keys), ...array_merge($keys, $args));

        $status   = is_array($result) ? ($result[0] ?? 'unknown') : 'unknown';
        $newPrice = is_array($result) && isset($result[1]) ? (float) $result[1] : 0.0;

        if ($status !== 'ok') {
            return [
                'status'    => $status,
                'new_price' => $newPrice,
                'reason'    => $this->reasonMessage($status, $newPrice),
            ];
        }

        /**
         * Redis kabul etti — MySQL'e append-only bid kaydı + auction.current_price güncellemesi.
         * Redis tek doğruluk kaynağı; MySQL eventual consistency.
         */
        try {
            DB::transaction(function () use ($auction, $customerId, $amount) {
                Bid::create([
                    'auction_id'  => $auction->id,
                    'customer_id' => $customerId,
                    'amount'      => $amount,
                ]);

                $auction->forceFill([
                    'current_price' => $amount,
                ])->save();
            });
        } catch (\Throwable $e) {
            report($e);
        }

        return [
            'status'    => 'ok',
            'new_price' => $newPrice,
        ];
    }

    protected function reasonMessage(string $status, float $current): string
    {
        return match ($status) {
            'rate_limited' => 'Çok hızlı teklif veriyorsunuz, lütfen biraz bekleyin.',
            'not_active'   => 'Bu mezat şu anda aktif değil.',
            'expired'      => 'Bu mezat sona erdi.',
            'too_low'      => "Teklifiniz çok düşük. Mevcut fiyat: {$current}.",
            'not_found'    => 'Mezat bulunamadı.',
            default        => 'Teklif işlenemedi.',
        };
    }

    /**
     * Auction oluşturulurken / aktif edilirken Redis hot-state'e hash yazar.
     * AuctionController@store zaten kendi Redis::hmset'ini yapıyor; bu metod
     * aktivasyon aşamasında (status=active) çağrılarak end_at_ts ve current_price
     * gibi bid scripti için gerekli alanları garanti altına alır.
     */
    public function warmUp(Auction $auction): void
    {
        $redisKey = "auction:{$auction->id}";
        $conn = $this->conn();

        $conn->hmset($redisKey, [
            'status'        => $auction->status,
            'start_at_ts'   => (string) ($auction->start_at?->timestamp ?? 0),
            'end_at_ts'     => (string) ($auction->end_at?->timestamp ?? 0),
            'current_price' => (string) ($auction->current_price ?? $auction->start_price ?? 0),
            'min_increment' => (string) ($auction->min_increment ?? 1),
            'start_price'   => (string) ($auction->start_price ?? 0),
        ]);

        if ($auction->end_at) {
            $conn->expireat($redisKey, $auction->end_at->timestamp + 86400);
        }
    }
}
