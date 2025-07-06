<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\ChefController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\UpdatesController;
use App\Http\Controllers\Web\MealController;

// Meals routes
// Route::get('/meals', [MealController::class, 'index'])->name('meals.index');

// // Cart & Checkout routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
//     Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.items.store');
//     Route::patch('/cart/items/{id}', [CartController::class, 'updateItem'])->name('cart.items.update');
//     Route::delete('/cart/items/{id}', [CartController::class, 'removeItem'])->name('cart.items.destroy');
//     Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

//     // checkout routes placeholders (PaymentController later)
//     Route::get('/checkout', function () {
//         return view('checkout.index');
//     })->name('checkout.index');
// });

// // الصفحة الرئيسية
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/updates/cart', [UpdatesController::class, 'cart']);

// // مسارات المحادثة
// Route::prefix('chat')->name('chat.')->middleware(['auth'])->group(function () {
//     Route::get('/', [ChatController::class, 'index'])->name('index');
//     Route::get('/{conversation}/messages', [ChatController::class, 'getMessages'])->name('messages');
//     Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
//     Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
// });

// // مسارات الوجبات (لوحة التحكم)
// Route::prefix('admin')->name('admin-')->middleware(['auth'])->group(function () {
//     Route::get('dishes', [DishController::class, 'index'])->name('dishes-index');
// });

// // مسارات المصادقة
// Auth::routes();

// // مسارات الزبون
// Route::middleware(['auth'])->group(function () {
//     // الصفحة الرئيسية للزبون
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
//     Route::get('/', function () {
//         return redirect('/home');
//     });

//     // طلبات الزبون السابقة
//     Route::get('/orders/history', [OrderController::class, 'history'])
//         ->name('orders.history');

//     // المطاعم المتاحة
//     Route::get('/restaurants', [RestaurantController::class, 'index'])
//         ->name('restaurants');

//     // المفضلة
//     Route::get('/favorites', [FavoriteController::class, 'index'])
//         ->name('favorites');
// });

// // مسارات الشيف
// Route::prefix('chef')->name('chef-')->middleware(['auth'])->group(function () {
//     // لوحة تحكم الشيف
//     Route::get('/dashboard', [ChefController::class, 'dashboard'])
//         ->name('dashboard');

//     // إدارة الوجبات
//     Route::resource('meals', DishController::class);

//     // طلبات المطعم
//     Route::get('/orders', [ChefController::class, 'orders'])
//         ->name('orders');

//     // تحديث حالة الطلب
//     Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
//         ->name('orders.update-status');
// });
// // مسارات الشيف
// Route::prefix('customer')->name('customer-')->middleware(['auth'])->group(function () {
//     // لوحة تحكم الشيف
//     Route::get('/dashboard', [CustomerController::class, 'dashboard'])
//         ->name('dashboard');

//     // إدارة الوجبات
//     Route::resource('meals', DishController::class);

//     // طلبات المطعم
//     Route::get('/orders', [CustomerController::class, 'orders'])
//         ->name('orders');

//     // تحديث حالة الطلب
//     Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
//         ->name('orders.update-status');
// });
