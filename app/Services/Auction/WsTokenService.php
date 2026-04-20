<?php

namespace App\Services\Auction;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

/**
 * Go WebSocket sunucusu için opaque token üretir.
 *
 * Go tarafındaki `validateOpaqueToken` şu şemayı bekler:
 *   Key  : opaque:token:{token}  (prefix YOK)
 *   Value: JSON { "user_id": int64, "room_id": string }
 *   TTL  : kısa süreli (ör. 60s)
 *
 * Bu nedenle prefix'siz `auction` Redis bağlantısını kullanıyoruz.
 */
class WsTokenService
{
    public function issue(int $customerId, string $roomId, int $ttlSeconds = 60): string
    {
        $token = Str::random(48);

        $payload = json_encode([
            'user_id' => $customerId,
            'room_id' => $roomId,
        ], JSON_UNESCAPED_UNICODE);

        Redis::connection('auction')->setex("opaque:token:{$token}", $ttlSeconds, $payload);

        return $token;
    }
}
