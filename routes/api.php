<?php


use Illuminate\Support\Facades\Auth;

// ==================== Auth ====================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ChatController;

// ==================== Profile ====================
use App\Http\Controllers\ChefOrderController;

use App\Http\Controllers\API\Chef\OrderController as ResturantOrderContrller; // Mohamed

// ==================== Categories & Dishes ====================
use App\Http\Controllers\API\ReviewController;

// ==================== Chef ====================
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\CategoryController;

use App\Http\Controllers\API\OtpLoginController;

use App\Http\Controllers\Api\Chef\ChefController;
// ==================== Orders, Cart, Payment ====================

use App\Http\Controllers\Api\Chef\DishController;
use App\Http\Controllers\API\Chef\IngredientsController;
use App\Http\Controllers\API\Chef\OrderController;
use App\Http\Controllers\API\OrderController as AliasOrderController;

use App\Http\Controllers\API\SocialAuthController;
// ==================== Reviews ====================

use App\Http\Controllers\API\ChefReviewsController;

use App\Http\Controllers\Customer\DishesController;
// ==================== Reviews ====================
use App\Http\Controllers\ShipmentAddressController;
use App\Http\Controllers\Customer\FavoriteController;

// ==================== Chat ====================

use App\Http\Controllers\API\Chef;

use App\Http\Controllers\API\Chef\StatisticsController;

//use App\Http\Controllers\ChefOrderController;
use App\Http\Controllers\API\CustomerProfileController;
use App\Http\Controllers\Customer\NotificationController;




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

Route::post('/email/verify', [OtpLoginController::class, 'verifyEmail']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('dishes/meal-type/{mealType}', [CategoryController::class, 'getDishesByMealType']);
Route::get('categories/{categoryId}/dishes', [CategoryController::class, 'getDishesByCategory']);
Route::get('dishes/meal-types', [CategoryController::class, 'mealTypes']);


// المدفوعات
Route::post('/payments', [PaymentController::class, 'processPayment']);
Route::post('/orders/{id}/update-payment-status', [PaymentController::class, 'updatePaymentStatus']);
Route::post('/orders/{id}/check-payment-status', [PaymentController::class, 'checkPaymentStatus']);
Route::post('/orders/{id}/refund', [PaymentController::class, 'refundPayment']);
Route::post('/payments', [PaymentController::class, 'processPayment']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);

// طرق الدفع
Route::get('/payment-methods', [PaymentController::class, 'addPaymentMethod']);
Route::post('/payment-methods', [PaymentController::class, 'storePaymentMethod']);
Route::get('/payment-methods/{id}', [PaymentController::class, 'getPaymentMethod']);

// المراجعات

Route::get('/reviews', [ReviewController::class, 'index']);


// Reviews
Route::get('/dishes/{dishId}/reviews', [ReviewController::class, 'dishReviews']);
Route::get('/chefs/{chefId}/reviews', [ReviewController::class, 'chefReviews']);
Route::get('chef_reviews/{chefId}', [ChefReviewsController::class, 'index']);


//  عرض الأطباق للعميل
Route::get('/client/meals', [DishesController::class, 'index']);

// عرض تفاصيل طبق
Route::get('/client/meals/{id}', [DishesController::class, 'show']);

// عرض أطباق بعد الفلترة
Route::get('/client/meals_filter/', [DishesController::class, 'filter']);

// البحث عن طبق أو مطعم معين
Route::get('/client/meals_search/', [DishesController::class, 'search']);


Route::get("ingredients", [IngredientsController::class, 'index']);


Route::controller(ChefController::class)->group(function () {
    Route::get("resturants/search", "searchChefs");
    Route::get("open-resturants", "getOpenChefs");
    Route::get("resturants/{id}", "showChefWithCategoriesAndMeals");
});
// ==================== Protected Routes (Sanctum) ====================
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(FavoriteController::class)->prefix('favorites')->group(function () {
        Route::get('/', 'index');           
        Route::post('add', 'store');        
        Route::delete('remove/{dishId}', 'destroy'); 
    });


    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'index']);
    Route::post('/profile', [CustomerProfileController::class, 'update']);
    // Edit profile and user information
    Route::put('/update/profile', [CustomerProfileController::class, 'profileInfoUpdate']);
    Route::put('/update/user', [CustomerProfileController::class, 'userInfoUpdate']);

    // Chef Meals
    Route::controller(DishController::class)->prefix("meals")->name("meals.")->group(function () {
        Route::get('/', 'index')->name("index");
        Route::get('/{id}', 'show')->name("show");
        Route::post('/', 'store')->name("store");
    });

    // Chat
    Route::controller(ChatController::class)->group(function () {
        Route::get('/conversations', 'getConversations');
        Route::post('/messages/send', 'sendMessage');
        Route::get('/conversations/{conversationId}', 'show');
        Route::delete("messages/{messageId}/destroy", 'destroyMessage');
        Route::post('/conversation/typing-status', 'typingStatus');
    });

    // Chef Orders
    Route::controller(ResturantOrderContrller::class)->prefix('chef/orders')->group(function () {
        Route::get('/running', 'runningOrders');
        Route::patch('/{orderId}/done', 'markAsDone');
        Route::patch('/{orderId}/cancel', 'cancelOrder');
    });

    // مسارات تتطلب مصادقة
    // سلة التسوق
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
    Route::post('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon']);

    // Statistics
    Route::prefix('chef/statistics')->group(function () {
        Route::get('/', [StatisticsController::class, 'statistics']);
    });


    // Orders - محمية بالمصادقة
    Route::get('/orders', [AliasOrderController::class, 'index']);
    Route::get('/orders/{id}', [AliasOrderController::class, 'show']);
    Route::post('/store/new/order', [AliasOrderController::class, 'store']);
    Route::post('/change/order/status/{id}', [AliasOrderController::class, 'changeOrderStatus']);
    Route::get('/orders/{id}/track', [AliasOrderController::class, 'trackOrder']);
    Route::put('/orders/{id}/cancel', [AliasOrderController::class, 'cancel']);

    // Orders Lists and filtering
    Route::get('/chef/completed-orders', [AliasOrderController::class, 'chefCompletedOrders'])->name('chef-completed-orders');
    Route::get('/chef/running-orders', [AliasOrderController::class, 'chefOngoingOrders'])->name('chef-running-orders');
    Route::get('/customer/orders', [AliasOrderController::class, 'customerOrders'])->name('customer-orders');
    Route::get('/chef/orders', [AliasOrderController::class, 'chefOrders'])->name('chef-orders');

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

    Route::get('/user/get/reviews', [ReviewController::class, 'userReviews']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Get all notifications for the logged-in chef
    Route::get('/notifications', [NotificationController::class, 'index']);

    // Get unread notifications only
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);

    // Mark all as read
    Route::post('/notifications/mark-as-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'done']);
    });

    // المراجعات
    Route::get('/reviews/{id}/show', [ReviewController::class, 'show']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Address
    Route::post('/address', [ShipmentAddressController::class, 'store']);
    Route::get('/my/addresses', [ShipmentAddressController::class, 'index']);
    Route::get('/default/address', [ShipmentAddressController::class, 'defaultAddress']);
    Route::get('/address/{id}', [ShipmentAddressController::class, 'show']);
    Route::put('/address/{id}/set-as-default', [ShipmentAddressController::class, 'setAsDefaultAddress']);
    Route::delete('/address/{id}/destroy', [ShipmentAddressController::class, 'destroy']);
    Route::put('/address/{id}/update', [ShipmentAddressController::class, 'update']);


    // الطلبات
    Route::get('/orders', [AliasOrderController::class, 'index']);
    Route::get('/orders/{id}', [AliasOrderController::class, 'show']);
    Route::post('/orders', [AliasOrderController::class, 'store']);
    Route::get('/get/customer/orders', [AliasOrderController::class, 'getCustomerOrders']);
    Route::get('/get/customer/orders/where', [AliasOrderController::class, 'getCustomerOrdersByStatus']);
    Route::get('/get/chef/orders/where', [AliasOrderController::class, 'getChefOrdersByStatus']);
    Route::get('/get/chef/orders', [AliasOrderController::class, 'getChefOrders']);
    Route::put('/orders/{id}/cancel', [AliasOrderController::class, 'cancel']);
    Route::get('/orders/{id}/track', [AliasOrderController::class, 'trackOrder']);
});


