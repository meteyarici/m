<?php

use App\Http\Controllers\Admin\AuctionModerationController;
use App\Http\Controllers\AuctionPageController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/ws-test', function () {
    return view('test');
});

Route::get('/ws-fire', function () {
    broadcast(new \App\Events\TestEvent("Selam WebSocket!"));
    return "Gönderildi";
});

/**
 * Auction storefront route'ları.
 *
 * Ana sayfa ve create-auction gibi mevcut blade'ler Shop paketinde kaldı.
 * Yeni listeleme/detay sayfaları bu grup altında.
 *
 * Shop paketindeki 'theme'/'locale'/'currency' middleware'leri kanal teması
 * (default), dil ve para birimini kuruyor. Bu olmazsa BagistoPlus Visual'ın
 * 'visual-debut' teması fallback olarak seçilip Vite manifest path'i bozuluyor.
 */
Route::middleware(['web', 'theme', 'locale', 'currency'])->group(function () {
    Route::controller(AuctionPageController::class)->group(function () {
        Route::get('/auctions', 'index')->name('shop.auctions.index');
        Route::get('/auctions/{id}', 'show')->whereNumber('id')->name('shop.auctions.show');

        Route::middleware('customer')->group(function () {
            Route::post('/auctions/{id}/ws-token', 'wsToken')
                ->whereNumber('id')
                ->name('shop.auctions.ws-token');
        });
    });
});

/**
 * Admin — Auction Moderation.
 *
 * Webkul\Admin paketine dokunmadan yeni moderation ekranları.
 * Admin guard/middleware'i AdminServiceProvider tarafından kaydediliyor.
 */
Route::middleware(['admin'])
    ->prefix(config('app.admin_url', 'admin'))
    ->group(function () {
        Route::controller(AuctionModerationController::class)->prefix('auctions')->group(function () {
            Route::get('/', 'index')->name('admin.auctions.index');
            Route::get('/{id}', 'show')->whereNumber('id')->name('admin.auctions.show');
            Route::post('/{id}/approve', 'approve')->whereNumber('id')->name('admin.auctions.approve');
            Route::post('/{id}/activate', 'activate')->whereNumber('id')->name('admin.auctions.activate');
            Route::post('/{id}/reject', 'reject')->whereNumber('id')->name('admin.auctions.reject');
            Route::post('/{id}/cancel', 'cancel')->whereNumber('id')->name('admin.auctions.cancel');
        });
    });
