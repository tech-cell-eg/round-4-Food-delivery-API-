<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DishController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ReviewController;

// مسارات المصادقة
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// مسارات عامة لا تتطلب مصادقة
Route::get('/categories', [CategoryController::class, 'index']); // عرض جميع الأقسام
Route::get('/categories/{id}', [CategoryController::class, 'show']); // عرض قسم معين
Route::get('/dishes', [DishController::class, 'index']); // عرض جميع الأطباق
Route::get('/dishes/{id}', [DishController::class, 'show']); // عرض طبق معين
Route::get('/dishes/{dishId}/reviews', [ReviewController::class, 'dishReviews']); // عرض مراجعات طبق معين
Route::get('/chefs/{chefId}/reviews', [ReviewController::class, 'chefReviews']); // عرض مراجعات طاهي معين

// مسارات تتطلب مصادقة

// معلومات المستخدم وتسجيل الخروج
Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);

// إدارة الأطباق (للطهاة)
Route::post('/dishes', [DishController::class, 'store']); // إضافة طبق جديد
Route::put('/dishes/{id}', [DishController::class, 'update']); // تحديث طبق موجود
Route::delete('/dishes/{id}', [DishController::class, 'destroy']); // حذف طبق

// سلة التسوق
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon']);

// الطلبات
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
Route::get('/orders/{id}/track', [OrderController::class, 'trackOrder']);

// المدفوعات
Route::post('/payments', [PaymentController::class, 'processPayment']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);

// المراجعات

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
Route::get('/user/reviews', [ReviewController::class, 'userReviews']);
