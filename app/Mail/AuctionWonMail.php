<?php

namespace App\Mail;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionWonMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Auction $auction, public Bid $winnerBid) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tebrikler! Mezatı kazandınız: '.($this->auction->title ?? ('#'.$this->auction->id)),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auction-won',
            with: [
                'auction'   => $this->auction,
                'winnerBid' => $this->winnerBid,
            ],
        );
    }
}
