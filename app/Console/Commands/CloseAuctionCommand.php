<?php

namespace App\Console\Commands;

use App\Events\AuctionClosed;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * Süresi dolmuş aktif mezatları kapatır, en yüksek tekliften kazananı belirler
 * ve AuctionClosed event'ini fırlatır.
 *
 *   php artisan auction:close              -> batch
 *   php artisan auction:close 42           -> tek id
 */
class CloseAuctionCommand extends Command
{
    protected $signature = 'auction:close {auction_id?}';
    protected $description = 'Süresi dolmuş mezatları kapatır ve kazananı belirler.';

    public function handle(): int
    {
        $id = $this->argument('auction_id');

        $query = Auction::query();

        if ($id) {
            $query->whereKey((int) $id);
        } else {
            $query->where('status', Auction::STATUS_ACTIVE)
                ->where('end_at', '<=', now());
        }

        $count = 0;

        $query->chunkById(100, function ($auctions) use (&$count) {
            foreach ($auctions as $auction) {
                if ($this->close($auction)) {
                    $count++;
                }
            }
        });

        $this->info("Closed {$count} auction(s).");

        return self::SUCCESS;
    }

    protected function close(Auction $auction): bool
    {
        if ($auction->status !== Auction::STATUS_ACTIVE) {
            return false;
        }

        $winnerBid = Bid::where('auction_id', $auction->id)
            ->orderByDesc('amount')
            ->orderBy('created_at')
            ->first();

        DB::transaction(function () use ($auction, $winnerBid) {
            $auction->status             = Auction::STATUS_CLOSED;
            $auction->closed_at          = now();
            $auction->winner_customer_id = $winnerBid?->customer_id;
            if ($winnerBid) {
                $auction->current_price = $winnerBid->amount;
            }
            $auction->save();
        });

        $redisKey = "auction:{$auction->id}";
        $conn = Redis::connection('auction');
        $conn->hset($redisKey, 'status', Auction::STATUS_CLOSED);
        if ($winnerBid) {
            $conn->hset($redisKey, 'winner_customer_id', (string) $winnerBid->customer_id);
        }

        $conn->publish("auction:{$auction->id}:bids", json_encode([
            'type'        => 'closed',
            'auction_id'  => $auction->id,
            'winner_id'   => $winnerBid?->customer_id,
            'final_price' => (float) ($winnerBid?->amount ?? $auction->current_price),
            'ts'          => time(),
        ], JSON_UNESCAPED_UNICODE));

        event(new AuctionClosed($auction, $winnerBid));

        $this->line("  - Auction #{$auction->id} closed. Winner: ".($winnerBid?->customer_id ?? 'none'));

        return true;
    }
}
