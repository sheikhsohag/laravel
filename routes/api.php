<?php

use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ImageController;
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