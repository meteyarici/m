<?php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/ws-test', function () {
    return view('test');
});

Route::get('/ws-fire', function () {
    broadcast(new \App\Events\TestEvent("Selam WebSocket!"));
    return "Gönderildi";
});
