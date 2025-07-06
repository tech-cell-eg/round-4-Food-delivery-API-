<?php


use Illuminate\Support\Facades\Auth;

// ==================== Auth ====================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;

// ==================== Profile ====================
use App\Http\Controllers\API\ChatController;

// ==================== Categories & Dishes ====================
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\API\OrderController;

// ==================== Chef ====================
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OtpLoginController;

// ==================== Orders, Cart, Payment ====================
use App\Http\Controllers\API\Chef\ChefController;
use App\Http\Controllers\API\Chef\DishController;
use App\Http\Controllers\API\SocialAuthController;

// ==================== Reviews ====================
use App\Http\Controllers\API\ChefReviewsController;
use App\Http\Controllers\Customer\DishesController;

// ==================== Chat ====================
use App\Http\Controllers\API\Chef\StatisticsController;
use App\Http\Controllers\API\CustomerProfileController;
use App\Http\Controllers\API\Chef\OrderController as ChefOrderController;

// ==================== Auth Routes ====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// معلومات المستخدم وتسجيل الخروج
Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('password')->group(function () {
    Route::post('/send_otp', [OtpLoginController::class, 'sendOtp']);
    Route::post('/login_otp', [OtpLoginController::class, 'loginWithOtp']);
    Route::post('/reset', [OtpLoginController::class, 'resetPassword']);
});

// مسارات تتطلب مصادقة
// سلة التسوق
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
Route::post('/cart/clear', [CartController::class, 'clearCart']);
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
Route::get('/payments/{id}', [PaymentController::class, 'checkPaymentStatus']);
Route::post('/payments/{id}/result', [PaymentController::class, 'updatePaymentResult']);

// طرق الدفع
Route::get('/payment-methods', [PaymentController::class, 'addPaymentMethod']);
Route::post('/payment-methods', [PaymentController::class, 'storePaymentMethod']);
Route::get('/payment-methods/{id}', [PaymentController::class, 'getPaymentMethod']);

// المراجعات

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
Route::get('/user/reviews', [ReviewController::class, 'userReviews']);

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

    // Get all notifications for the logged-in chef
    Route::get('/notifications', function () {
        return Auth::user()->notifications;
    });

    // Get unread notifications only
    Route::get('/notifications/unread', function () {
        return Auth::user()->unreadNotifications;
    });

    // Mark all as read
    Route::post('/notifications/mark-as-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'done']);
    });
});
