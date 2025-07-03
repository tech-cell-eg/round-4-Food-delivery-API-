<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==================== Auth ====================
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OtpLoginController;
use App\Http\Controllers\API\SocialAuthController;

// ==================== Profile ====================
use App\Http\Controllers\API\CustomerProfileController;

// ==================== Categories & Dishes ====================
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Customer\DishesController;

// ==================== Chef ====================
use App\Http\Controllers\API\Chef\ChefController;
use App\Http\Controllers\API\Chef\DishController;
use App\Http\Controllers\API\Chef\OrderController as ChefOrderController;
use App\Http\Controllers\API\Chef\StatisticsController;

// ==================== Orders, Cart, Payment ====================
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\PaymentController;

// ==================== Reviews ====================
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ChefReviewsController;

// ==================== Chat ====================
use App\Http\Controllers\API\ChatController;

// ==================== Auth Routes ====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('password')->group(function () {
    Route::post('/send_otp', [OtpLoginController::class, 'sendOtp']);
    Route::post('/login_otp', [OtpLoginController::class, 'loginWithOtp']);
    Route::post('/reset', [OtpLoginController::class, 'resetPassword']);
});

Route::get('/auth/redirect/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/callback/google', [SocialAuthController::class, 'handleGoogleCallback']);

// ==================== Public Customer Routes ====================
// Categories & Dishes
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/meal_types', [CategoryController::class, 'mealTypes']);
Route::get('categories/{category}/dishes', [CategoryController::class, 'getDishesByCategory']);
Route::get('dishes/meal-type/{mealType}', [CategoryController::class, 'getDishesByMealType']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Chef
Route::get('/open-resturants', [ChefController::class, 'getOpenChefs'])->name("getOpenChefs");
Route::get('/resturants/{id}', [ChefController::class, 'showChefWithCategoriesAndMeals'])->name("showChefWithCategoriesAndMeals");

// Client Dishes
Route::get('/client/meals', [DishesController::class, 'index']);
Route::get('/client/meals/{id}', [DishesController::class, 'show']);
Route::get('/client/meals_filter', [DishesController::class, 'filter']);
Route::get('/client/meals_search', [DishesController::class, 'search']);

// Reviews
Route::get('/dishes/{dishId}/reviews', [ReviewController::class, 'dishReviews']);
Route::get('/chefs/{chefId}/reviews', [ReviewController::class, 'chefReviews']);
Route::get('chef_reviews/{chefId}', [ChefReviewsController::class, 'index']);

// ==================== Protected Routes (Sanctum) ====================
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'index']);
    Route::post('/profile', [CustomerProfileController::class, 'update']);

    // Chef Meals
    Route::controller(DishController::class)->prefix("meals")->name("meals.")->group(function () {
        Route::get('/', 'index')->name("index");
        Route::get('/{id}', 'show')->name("show");
        Route::post('/', 'store')->name("store");
    });

    // Chef Orders
    Route::controller(ChefOrderController::class)->prefix('chef/orders')->group(function () {
        Route::get('/running', 'runningOrders');
        Route::patch('/{orderId}/done', 'markAsDone');
        Route::patch('/{orderId}/cancel', 'cancelOrder');
    });

    // Statistics
    Route::prefix('chef/statistics')->group(function () {
        Route::get('/', [StatisticsController::class, 'statistics']);
    });

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/orders/{id}/track', [OrderController::class, 'trackOrder']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
    Route::post('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon']);

    // Payments
    Route::post('/payments', [PaymentController::class, 'processPayment']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // Reviews
    Route::get('/user/reviews', [ReviewController::class, 'userReviews']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Chat
    Route::controller(ChatController::class)->group(function () {
        Route::post('/messages/send', 'sendMessage');
        Route::get('/conversations/{conversationId}', 'show');
        Route::delete("messages/{messageId}/destroy", 'destroyMessage');
    });
});
