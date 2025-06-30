<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DishController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;

// مسارات المستخدم
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// مسارات الأطباق (متاحة للجميع)
Route::prefix('dishes')->group(function () {
    Route::get('/', [DishController::class, 'index']);
    Route::get('/categories', [DishController::class, 'categories']);
    Route::get('/{id}', [DishController::class, 'show']);
});

// المسارات التي تتطلب مصادقة
Route::middleware('auth:sanctum')->group(function () {
    // مسارات سلة التسوق
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'addItem']);
        Route::put('/update/{id}', [CartController::class, 'updateItem']);
        Route::delete('/remove/{id}', [CartController::class, 'removeItem']);
        Route::delete('/clear', [CartController::class, 'clearCart']);
    });

    // مسارات الطلبات
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/cancel/{id}', [OrderController::class, 'cancel']);
    });

    // مسارات المدفوعات
    Route::prefix('payments')->group(function () {
        Route::post('/process', [PaymentController::class, 'processPayment']);
        Route::get('/status/{orderId}', [PaymentController::class, 'checkPaymentStatus']);
    });
});
