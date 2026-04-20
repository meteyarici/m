<?php

namespace App\Events;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionClosed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Auction $auction,
        public ?Bid $winnerBid = null,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('auction.'.$this->auction->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'auction.closed';
    }

    public function broadcastWith(): array
    {
        return [
            'auction_id'  => $this->auction->id,
            'winner_id'   => $this->winnerBid?->customer_id,
            'final_price' => (float) ($this->winnerBid?->amount ?? $this->auction->current_price),
            'closed_at'   => $this->auction->closed_at?->toIso8601String(),
        ];
    }
}
