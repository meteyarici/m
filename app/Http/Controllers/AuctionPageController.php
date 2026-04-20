<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Services\Auction\WsTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * Mezat storefront sayfaları (detay + listeleme).
 *
 * Route'lar `routes/web.php` altında tanımlı. Shop paketinin özelleştirilmiş
 * view component'leri (`x-shop::layouts`) burada da kullanılabilir çünkü Shop
 * servis sağlayıcısı view namespace'ini global kaydeder.
 */
class AuctionPageController extends Controller
{
    public function __construct(protected WsTokenService $wsTokenService) {}

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'active');

        $query = Auction::query()->with('product');

        $query->when($filter === 'active', fn ($q) => $q
            ->where('status', Auction::STATUS_ACTIVE)
            ->where('end_at', '>', now())
        );

        $query->when($filter === 'upcoming', fn ($q) => $q
            ->whereIn('status', [Auction::STATUS_PENDING, Auction::STATUS_APPROVED])
            ->where('start_at', '>', now())
        );

        $query->when($filter === 'closed', fn ($q) => $q
            ->where(function ($inner) {
                $inner->whereIn('status', [Auction::STATUS_CLOSED, Auction::STATUS_CANCELLED, Auction::STATUS_REJECTED])
                    ->orWhere('end_at', '<=', now());
            })
        );

        $auctions = $query->orderByDesc('created_at')->paginate(12);

        return view('shop::auctions.index', [
            'auctions' => $auctions,
            'filter'   => $filter,
        ]);
    }

    public function show(int $id)
    {
        $auction = Auction::with(['product', 'bids' => fn ($q) => $q->orderByDesc('created_at')->limit(10)])
            ->findOrFail($id);

        /**
         * Redis hot-state'ten anlık fiyatı oku (prefix'siz 'auction' bağlantısı);
         * hashte yoksa DB değerini kullan.
         */
        $redisKey = "auction:{$auction->id}";
        $current  = Redis::connection('auction')->hget($redisKey, 'current_price');
        $livePrice = $current !== null ? (float) $current : (float) $auction->current_price;

        $wsEndpoint = config('auction.ws_endpoint', env('AUCTION_WS_PUBLIC_URL', 'ws://localhost:8081/ws'));

        return view('shop::auctions.show', [
            'auction'     => $auction,
            'livePrice'   => $livePrice,
            'wsEndpoint'  => $wsEndpoint,
        ]);
    }

    /**
     * WebSocket için opaque token üretir. Go WS tarafındaki
     * `validateOpaqueToken` fonksiyonu ile uyumlu şemada Redis'e yazılır.
     *
     * Route: POST /auctions/{id}/ws-token   (middleware: customer)
     */
    public function wsToken(int $id): JsonResponse
    {
        $customer = auth()->guard('customer')->user();
        if (! $customer) {
            return response()->json(['success' => false, 'message' => 'Giriş yapmalısınız.'], 401);
        }

        $auction = Auction::findOrFail($id);

        $token = $this->wsTokenService->issue(
            customerId: (int) $customer->id,
            roomId: 'auction:'.$auction->id,
            ttlSeconds: 60,
        );

        return response()->json([
            'success' => true,
            'token'   => $token,
            'room'    => 'auction:'.$auction->id,
        ]);
    }
}
