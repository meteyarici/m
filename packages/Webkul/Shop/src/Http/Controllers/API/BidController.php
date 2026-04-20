<?php

namespace Webkul\Shop\Http\Controllers\API;

use App\Models\Auction;
use App\Services\Auction\BidService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Shop API BidController.
 *
 * Mevcut dosya korundu (`api.php` bu import'u kullanıyor). Placeholder
 * `return true;` gövdesi, Faz 2 kapsamında atomik BidService çağrısıyla
 * değiştirildi.
 *
 * Route:  POST /api/auction/bid   (customer middleware)
 * Name:   shop.api.auction.bid.make
 */
class BidController extends APIController
{
    public function __construct(protected BidService $bidService) {}

    public function make(Request $request): JsonResponse
    {
        $data = $request->validate([
            'auction_id' => 'required|integer|exists:auctions,id',
            'amount'     => 'required|numeric|min:0',
        ]);

        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return response()->json([
                'success' => false,
                'message' => 'Teklif vermek için giriş yapmalısınız.',
            ], 401);
        }

        $auction = Auction::find($data['auction_id']);

        if (! $auction) {
            return response()->json([
                'success' => false,
                'message' => 'Mezat bulunamadı.',
            ], 404);
        }

        /**
         * Kendi mezatına teklif veremez.
         */
        if ((int) $auction->user_id === (int) $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Kendi mezatınıza teklif veremezsiniz.',
            ], 422);
        }

        $result = $this->bidService->place(
            $auction,
            (int) $customer->id,
            (float) $data['amount']
        );

        $statusCode = match ($result['status']) {
            'ok'           => 200,
            'rate_limited' => 429,
            'not_found'    => 404,
            'too_low',
            'not_active',
            'expired'      => 422,
            default        => 400,
        };

        return response()->json([
            'success'       => $result['status'] === 'ok',
            'status'        => $result['status'],
            'message'       => $result['reason'] ?? 'Teklif kaydedildi.',
            'current_price' => $result['new_price'],
        ], $statusCode);
    }
}
