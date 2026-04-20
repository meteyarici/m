<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Auction;


class ActivateAuctionCommand extends Command
{
    protected $signature = 'auction:activate {auction_id}';
    protected $description = 'Set auction status to active via event system';

    public function handle()
    {
        $auctionId = $this->argument('auction_id');

        $auction = Auction::find($auctionId);

        if (! $auction) {
            $this->error('Auction not found.');
            return Command::FAILURE;
        }

        //event(new ActionActivated($auction));

        $auction = Auction::findOrFail($this->argument('auction_id'));
        $auction->status = 'active';
        $auction->save();

        $this->info("Auction #{$auctionId} activated successfully.");

        return Command::SUCCESS;
    }
}
