<?php

namespace App\Listeners;

use App\Events\AuctionClosed;
use App\Mail\AuctionWonMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Models\Customer;

class SendAuctionWonNotification implements ShouldQueue
{
    public function handle(AuctionClosed $event): void
    {
        if (! $event->winnerBid) {
            return;
        }

        $customer = Customer::find($event->winnerBid->customer_id);
        if (! $customer || ! $customer->email) {
            return;
        }

        Mail::to($customer->email)->send(new AuctionWonMail($event->auction, $event->winnerBid));
    }
}
