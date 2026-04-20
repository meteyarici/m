<?php

namespace App\Events;

use App\Models\Auction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Bir mezat aktif duruma geçtiğinde fırlatılan event.
 *
 * NOT: Sınıf adı eski kodda "ActionActivated" olarak yazılmış (typo).
 * Çağıran yerleri bozmamak için ad korundu; içerik bu fazda tamamlandı.
 */
class ActionActivated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Auction $auction) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('auction.'.$this->auction->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'auction.activated';
    }

    public function broadcastWith(): array
    {
        return [
            'auction_id'    => $this->auction->id,
            'title'         => $this->auction->title,
            'start_at'      => $this->auction->start_at?->toIso8601String(),
            'end_at'        => $this->auction->end_at?->toIso8601String(),
            'current_price' => (float) $this->auction->current_price,
        ];
    }
}
