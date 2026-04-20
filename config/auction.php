<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WebSocket public endpoint
    |--------------------------------------------------------------------------
    |
    | Tarayıcıdan erişilebilir WS URL'i. Docker içinden değil, user-agent
    | tarafından kullanılacak olan adres burada tutulur. Gerekirse TLS için
    | wss:// şeması verilebilir.
    */
    'ws_endpoint' => env('AUCTION_WS_PUBLIC_URL', 'ws://localhost:8081/ws'),

    /*
    |--------------------------------------------------------------------------
    | Rate-limit ayarları
    |--------------------------------------------------------------------------
    |
    | BidService Lua script bu değerleri ARGV üzerinden kullanır. Buradaki
    | değerler service içinde sabit; ileride ortamdan okumak istenirse
    | BidService'a enjekte edilebilir.
    */
    'bid_rate_limit' => [
        'max' => (int) env('AUCTION_BID_RATE_MAX', 5),
        'ttl' => (int) env('AUCTION_BID_RATE_TTL', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis key prefix'leri
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'hot_key'         => 'auction:{id}',
        'leaderboard_key' => 'auction:{id}:leaderboard',
        'pubsub_channel'  => 'auction:{id}:bids',
        'opaque_token'    => 'opaque:token:{token}',
    ],
];
