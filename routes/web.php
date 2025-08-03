<?php

use App\Http\Controllers\webView\AlpineJsController;
use App\Http\Controllers\webView\ExtendFunctionalityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('web')->group(function(){
    Route::get('/', [ExtendFunctionalityController::class, 'index']);
    Route::get('/alpine', [AlpineJsController::class, 'index']);
});


use App\Events\WebSocketEvent;

Route::get('/test-broadcast', function () {
    broadcast(new WebSocketEvent('Testing Broadcast from Postman'));
    return response()->json([
        'status' => 'broadcasted'
    ]);
});
