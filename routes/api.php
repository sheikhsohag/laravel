<?php

use App\Http\Controllers\BulkEmailController;
use App\Http\Controllers\EgerLoading\EgerLoading;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Macro\MacroController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceContainer\ExampleController;
use App\Http\Controllers\ServiceContainer\PaymentController;
use App\Http\Controllers\ServiceContainer\UserController;
use App\Providers\SmsServiceProvider;
use Box\Spout\Common\Entity\Row;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DockerController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;




Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('index')->group(function(){
    Route::post('/', [AuthController::class, 'index']);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('users')->group(function(){
    Route::get('/', [UserController::class, 'index']);
});


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

Route::prefix('job')->group(function(){
Route::post('/send-bulk-emails', [BulkEmailController::class, 'sendBulkEmails']);
Route::get('/job-status/{jobId}', [BulkEmailController::class, 'getJobStatus']);
Route::get('/batch-progress/{batchId}', [BulkEmailController::class, 'getBatchProgress']);
});


Route::prefix('sc')->group( function () {
Route::get('/', [ExampleController::class, 'index']);
Route::get('/register', [UserController::class, 'register']);
// Route::get('/pay', [PaymentController::class, 'pay']);
Route::get('/pay', [PaymentController::class, 'payWithGateway']);
});

Route::prefix('macro')->group(function () {
    Route::get('/', [MacroController::class, 'index']);
});


Route::prefix('eger-loading')->group(function(){
    Route::get('/', [EgerLoading::class, 'index']);
});

Route::prefix('products')->group(function(){
    Route::post('/', [ProductController::class, 'store']);
    Route::post('/all', [ProductController::class, 'index']);
});


Route::get('/cache-test', function() {
    Cache::put('test_key', 'test_value', now()->addMinutes(10));
    return [
        'value' => Cache::get('test_key'),
        'keys' => Redis::connection('cache')->keys('*')
    ];
});

Route::get('/docker', [DockerController::class, 'index']);

Route::get('/docker/{id}', [DockerController::class, 'show']);

Route::get('/docker/{id}/edit', [DockerController::class, 'edit']);

Route::prefix('docker-api')->group(function(){
    Route::post('/', [DockerController::class, 'create']);
    Route::put('/{id}', [DockerController::class, 'update']);
    Route::delete('/{id}', [DockerController::class, 'destroy']);
});
