<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuctionController extends Controller
{
    public function __construct() {}

    public function index() {}

    public function upload(Request $request)
    {
        // =============================
        // RATE LIMIT (Redis)
        // =============================
        $ip = $request->ip();
        $key = "auction:upload:{$ip}";
        $limit = 10;      // max 10 upload
        $ttl = 60;        // 60 saniye

        $count = Redis::incr($key);

        if ($count === 1) {
            Redis::expire($key, $ttl);
        }

        if ($count > $limit) {
            return response()->json([
                'message' => 'Çok fazla upload denemesi. Lütfen bekleyin.',
            ], 429);
        }

        // =============================
        // VALIDATION
        // =============================
        $request->validate([
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
            ],
        ]);

        // =============================
        // STORE FILE
        // =============================
        $path = $request->file('file')->store(
            'auctions/tmp',
            'public'
        );

        // =============================
        // RESPONSE
        // =============================
        return response()->json([
            'success' => true,
            'url'     => Storage::disk('public')->url($path),
            'path'    => $path,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title'          => 'required|string|max:255',
            'images'         => 'required|array|min:1',
            'start_price'    => 'required|numeric|min:0',
            'buy_now_price'  => 'nullable|numeric|min:0',
            'min_increment'  => 'required|numeric|min:1',
            'start_at'       => 'required|date',
            'end_at'         => 'required|date|after:start_at',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $sku = Str::slug($request->title).'-'.Str::random(6);

                $productId = DB::table('products')->insertGetId([
                    'sku'                 => $sku,
                    'type'                => 'simple',
                    'attribute_family_id' => 1,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                // Resim yolunu temizle
                $mainImage = isset($request->images[0])
                    ? str_replace(url('storage').'/', '', $request->images[0])
                    : null;

                $auctionId = DB::table('auctions')->insertGetId([
                    'user_id'              => 1, // Test için sabit, normalde auth()->id()
                    'product_id'           => $productId,
                    'title'                => $request->title,
                    'description'          => $request->description,
                    'image'                => $mainImage,
                    'start_at'             => $request->start_at,
                    'end_at'               => $request->end_at,
                    'start_price'          => $request->start_price,
                    'current_price'        => $request->start_price,
                    'min_price'            => $request->min_price ?? 0,
                    'buy_now_price'        => $request->buy_now_price ?: null,
                    'min_increment'        => $request->min_increment,
                    'is_buy_now_available' => ($request->buy_now_price > 0) ? 1 : 0,
                    'status'               => 'pending',
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);

                // Image Tablosu Kaydı
                if ($request->has('images')) {
                    $productImageData = [];
                    foreach ($request->images as $index => $imageUrl) {
                        $productImageData[] = [
                            'product_id' => $productId,
                            'path'       => str_replace(url('storage').'/', '', $imageUrl),
                            'position'   => $index + 1,
                            'type'       => 'images',
                        ];
                    }
                    DB::table('product_images')->insert($productImageData);
                }

                $description = !empty($request->description) ? $request->description : 'Açıklama eklenmedi.';

                $attributeValues = [
                    ['product_id' => $productId, 'attribute_id' => 1, 'text_value' => $sku, 'unique_id' => $productId.'|1', 'channel' => null, 'locale' => null, 'boolean_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 2, 'text_value' => $request->title, 'unique_id' => 'tr|default|'.$productId.'|2', 'channel' => 'default', 'locale' => 'tr', 'boolean_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 3, 'text_value' => Str::slug($request->title), 'unique_id' => 'tr|default|'.$productId.'|3', 'channel' => 'default', 'locale' => 'tr', 'boolean_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 11, 'float_value' => $request->start_price, 'unique_id' => 'default|'.$productId.'|11', 'channel' => 'default', 'locale' => null, 'text_value' => null, 'boolean_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 8, 'boolean_value' => 0, 'unique_id' => 'default|'.$productId.'|8', 'channel' => 'default', 'locale' => null, 'text_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 9, 'text_value' => $description, 'unique_id' => 'tr|default|'.$productId.'|9', 'channel' => 'default', 'locale' => 'tr', 'boolean_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 10, 'text_value' => $description, 'unique_id' => 'tr|default|'.$productId.'|10', 'channel' => 'default', 'locale' => 'tr', 'boolean_value' => null, 'float_value' => null],
                    ['product_id' => $productId, 'attribute_id' => 22, 'text_value' => '0.0000', 'unique_id' => $productId.'|22', 'channel' => null, 'locale' => null, 'boolean_value' => null, 'float_value' => null],
                ];
                DB::table('product_attribute_values')->insert($attributeValues);

                DB::table('product_categories')->insert(['product_id' => $productId, 'category_id' => 3]);
                DB::table('product_channels')->insert(['product_id' => $productId, 'channel_id' => 1]);

                DB::table('product_inventories')->insert([
                    'qty'                 => 1,
                    'product_id'          => $productId,
                    'vendor_id'           => 0,
                    'inventory_source_id' => 1,
                ]);

                DB::afterCommit(function () use ($productId, $auctionId, $request, $sku, $mainImage) {

                    $redisKey = "auction:$auctionId";


                    Redis::hmset($redisKey, [
                        'auction_id'           => $auctionId,
                        'product_id'           => $productId,
                        'sku'                  => $sku,
                        'title'                => $request->title,
                        'description'          => $request->description,
                        'image'                => $mainImage,
                        'status'               => 'pending',
                        'start_at'             => $request->start_at,
                        'end_at'               => $request->end_at,
                        'start_price'          => $request->start_price,
                        'current_price'        => $request->start_price,
                        'buy_now_price'        => $request->buy_now_price ?: 0,
                        'min_increment'        => $request->min_increment,
                        'is_buy_now_available' => ($request->buy_now_price > 0) ? 1 : 0,
                        'delivery_method'      => $request->delivery_method ?? 'optional',
                        'delivery_note'        => $request->delivery_note ?? '',
                        'city'                 => $request->city ?? '',
                        'district'             => $request->district ?? '',
                        'category_suggestion'  => $request->category_suggestion ?? null,
                    ]);

                    // Opsiyonel: Redis verisi için bir expire süresi (Mezat bitişinden 24 saat sonra silinsin)
                    $expireTime = strtotime($request->end_at) + 86400;
                    Redis::expireat($redisKey, $expireTime);

                    Artisan::queue('indexer:index');
                });

                return response()->json([
                    'success'    => true,
                    'auction_id' => $auctionId,
                    'product_id' => $productId,
                    'message'    => 'Mezat başarıyla oluşturuldu',
                ]);
            });

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
