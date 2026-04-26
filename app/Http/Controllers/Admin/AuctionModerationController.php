<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Services\Auction\BidService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Admin moderation controller.
 *
 * Webkul\Admin paketine dokunmadan, admin prefix'li route grubu altında
 * (admin/auctions) mezat moderasyonu yapar. `x-admin::layouts` component'i
 * global kullanılabildiği için özel view yolu yeterlidir.
 *
 * Kural gereği Admin dashboard blade'leri KORUMA listesinde; burada sadece
 * yeni view'lar (resources/views/admin/auctions/*) yaratılır.
 */
class AuctionModerationController extends Controller
{
    public function __construct(protected BidService $bidService) {}

    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Auction::query()->with('product')->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        return view('admin.auctions.index', [
            'auctions' => $query->paginate(20)->withQueryString(),
            'status'   => $status,
        ]);
    }

    public function show(int $id): View
    {
        $auction = Auction::with(['product', 'bids' => fn ($q) => $q->orderByDesc('created_at')->limit(50)])
            ->findOrFail($id);

        return view('admin.auctions.show', compact('auction'));
    }

    public function approve(int $id): RedirectResponse
    {
        $auction = Auction::findOrFail($id);

        if (! in_array($auction->status, [Auction::STATUS_PENDING], true)) {
            return back()->with('error', 'Yalnızca bekleyen mezatlar onaylanabilir.');
        }

        $auction->status = Auction::STATUS_APPROVED;
        $auction->save();

        \Illuminate\Support\Facades\Redis::connection('auction')
            ->hset("auction:{$auction->id}", 'status', Auction::STATUS_APPROVED);

        return back()->with('success', "Mezat #{$auction->id} onaylandı.");
    }

    public function activate(int $id): RedirectResponse
    {
        $auction = Auction::findOrFail($id);

        if (! in_array($auction->status, [Auction::STATUS_PENDING, Auction::STATUS_APPROVED, Auction::STATUS_PAUSED], true)) {
            return back()->with('error', 'Bu mezat şu an aktif edilemez.');
        }

        $auction->status = Auction::STATUS_ACTIVE;
        $auction->save();

        $this->publishProduct((int) $auction->product_id);

        $this->bidService->warmUp($auction);

        return back()->with('success', "Mezat #{$auction->id} aktif edildi.");
    }

    /**
     * Mezat aktif edildiğinde ilgili ürünü "yayında" hale getirir:
     *   - status attribute (id=8) → 1
     *   - visible_individually attribute (id=7) → 1 (garanti)
     * Ardından Bagisto indexer'ını senkron çalıştırır; böylece product_flat
     * ve ElasticSearch (etkinse) güncellenir ve ürün aramada/kategoride görünür.
     *
     * Not: `Artisan::queue()` yerine `Artisan::call()` kullanılıyor çünkü şu an
     * queue worker çalışmıyor. Worker devreye alınırsa burayı queue'ya çevirebiliriz.
     */
    protected function publishProduct(int $productId): void
    {
        if (! $productId) {
            return;
        }

        DB::table('product_attribute_values')
            ->where('product_id', $productId)
            ->where('attribute_id', 8)
            ->update(['boolean_value' => 1]);

        DB::table('product_attribute_values')
            ->updateOrInsert(
                ['product_id' => $productId, 'attribute_id' => 7, 'channel' => 'default', 'locale' => null],
                [
                    'boolean_value' => 1,
                    'text_value'    => null,
                    'float_value'   => null,
                    'unique_id'     => 'default|'.$productId.'|7',
                ]
            );

        Artisan::call('indexer:index', ['--mode' => ['full']]);
    }

    public function reject(int $id): RedirectResponse
    {
        $auction = Auction::findOrFail($id);

        if ($auction->status === Auction::STATUS_ACTIVE) {
            return back()->with('error', 'Aktif bir mezat reddedilemez; önce iptal edin.');
        }

        $auction->status = Auction::STATUS_REJECTED;
        $auction->save();

        \Illuminate\Support\Facades\Redis::connection('auction')
            ->hset("auction:{$auction->id}", 'status', Auction::STATUS_REJECTED);

        return back()->with('success', "Mezat #{$auction->id} reddedildi.");
    }

    public function cancel(int $id): RedirectResponse
    {
        $auction = Auction::findOrFail($id);

        $auction->status    = Auction::STATUS_CANCELLED;
        $auction->closed_at = now();
        $auction->save();

        \Illuminate\Support\Facades\Redis::connection('auction')
            ->hset("auction:{$auction->id}", 'status', Auction::STATUS_CANCELLED);

        return back()->with('success', "Mezat #{$auction->id} iptal edildi.");
    }
}
