<?php


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\Api\Chef\ChefController;
use App\Http\Controllers\Api\Chef\DishController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ReviewController;


Route::get('categories', [CategoryController::class, "index"]);
Route::get('categories/meal_types', [CategoryController::class, "mealTypes"]);
Route::get('categories/{category}/dishes', [CategoryController::class, 'getDishesByCategory']);
Route::get('dishes/meal-type/{mealType}', [CategoryController::class, 'getDishesByMealType']);

Route::controller(ChefController::class)->group(function () {
    Route::get('/open-resturants', 'getOpenChefs')->name("getOpenChefs");
    Route::get('/resturants/{id}', 'showChefWithCategoriesAndMeals')->name("showChefWithCategoriesAndMeals");
});


Route::controller(DishController::class)->prefix("meals")->name("meals.")/*->middleware("auth:sanctum")*/->group(function () {
    Route::get('/', 'index')->name("index");
    Route::get('/{id}', 'show')->name("show");
    Route::post('/', 'store')->name("store");
});

// مسارات المصادقة
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// مسارات عامة لا تتطلب مصادقة
Route::get('/categories', [CategoryController::class, 'index']); // عرض جميع الأقسام
Route::get('/categories/{id}', [CategoryController::class, 'show']); // عرض قسم معين
Route::get('/dishes/{dishId}/reviews', [ReviewController::class, 'dishReviews']); // عرض مراجعات طبق معين
Route::get('/chefs/{chefId}/reviews', [ReviewController::class, 'chefReviews']); // عرض مراجعات طاهي معين

// مسارات تتطلب مصادقة

// معلومات المستخدم وتسجيل الخروج
Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);


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
