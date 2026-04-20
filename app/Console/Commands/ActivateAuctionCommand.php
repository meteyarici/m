<?php

namespace App\Console\Commands;

use App\Events\ActionActivated;
use App\Models\Auction;
use App\Services\Auction\BidService;
use Illuminate\Console\Command;

class ActivateAuctionCommand extends Command
{
    /**
     * İki mod desteklenir:
     *   php artisan auction:activate 42        -> tek id aktif et (geriye dönük uyum)
     *   php artisan auction:activate --batch   -> start_at <= now, status IN (pending,approved) toplu aktif et
     */
    protected $signature = 'auction:activate
        {auction_id? : Belirli mezat id}
        {--batch : Zamanı gelmiş tüm mezatları aktif et}';

    protected $description = 'Mezatı/mezatları aktif duruma getirir ve Redis hot-state\'i ısıtır.';

    public function handle(BidService $bidService): int
    {
        if ($this->option('batch')) {
            return $this->runBatch($bidService);
        }

        $id = $this->argument('auction_id');
        if (! $id) {
            $this->error('auction_id veya --batch gerekli.');
            return self::FAILURE;
        }

        return $this->activate((int) $id, $bidService) ? self::SUCCESS : self::FAILURE;
    }

    protected function runBatch(BidService $bidService): int
    {
        $count = 0;

        Auction::query()
            ->whereIn('status', [Auction::STATUS_PENDING, Auction::STATUS_APPROVED])
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now())
            ->chunkById(100, function ($auctions) use ($bidService, &$count) {
                foreach ($auctions as $auction) {
                    if ($this->activate($auction->id, $bidService, $auction)) {
                        $count++;
                    }
                }
            });

        $this->info("Batch complete: {$count} auction(s) activated.");

        return self::SUCCESS;
    }

    protected function activate(int $id, BidService $bidService, ?Auction $auction = null): bool
    {
        $auction = $auction ?: Auction::find($id);

        if (! $auction) {
            $this->error("Auction #{$id} not found.");
            return false;
        }

        if ($auction->status === Auction::STATUS_ACTIVE) {
            $this->warn("Auction #{$id} zaten aktif.");
            return false;
        }

        $auction->status = Auction::STATUS_ACTIVE;
        $auction->save();

        $bidService->warmUp($auction);

        event(new ActionActivated($auction));

        $this->info("Auction #{$id} activated.");

        return true;
    }
}
