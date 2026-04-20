<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository;

class BidController extends APIController
{
    public function __construct(ProductRepository $product) {}

    public function make(): JsonResource
    {

       return true;
        return new JsonResource([
            'message' => trans('shop::app.customers.account.wishlist.success'),
        ]);
    }
}
