<?php

use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use Box\Spout\Common\Entity\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('excel')->group(function(){
    Route::post('/',[ExcelImportController::class,'store']);
});


Route::prefix('file')->group(function(){
    Route::post('/', [ImageController::class, 'store']);
    Route::post('/{id}', [ImageController::class, 'show']);
});

Route::prefix('send-mail')->group(function(){
    Route::post('/', [MailController::class, 'sendMail']);
});
Route::prefix('notification')->group(function(){
    Route::post('/{user_id}', [NotificationController::class, 'readNotification']);
    Route::post('/{user_id}/{notification_id}', [NotificationController::class, 'markAsRead']);
});