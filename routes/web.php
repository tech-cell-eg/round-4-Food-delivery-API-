<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\UpdatesController;

// Cart & Checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\Web\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items', [\App\Http\Controllers\Web\CartController::class, 'addItem'])->name('cart.items.store');
    Route::patch('/cart/items/{id}', [\App\Http\Controllers\Web\CartController::class, 'updateItem'])->name('cart.items.update');
    Route::delete('/cart/items/{id}', [\App\Http\Controllers\Web\CartController::class, 'removeItem'])->name('cart.items.destroy');
    Route::post('/cart/clear', [\App\Http\Controllers\Web\CartController::class, 'clear'])->name('cart.clear');

    // checkout routes placeholders (PaymentController later)
    Route::get('/checkout', function () {
        return view('checkout.index');
    })->name('checkout.index');
});

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});
Route::get('/updates/cart', [UpdatesController::class, 'cart']);

// مسارات المحادثة
Route::prefix('chat')->name('chat.')->middleware(['auth'])->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/{conversation}/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
});

// مسارات الوجبات (لوحة التحكم)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('dishes', [DishController::class, 'index'])->name('dishes.index');
});

// مسارات المصادقة
Auth::routes();


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return redirect('/home');
});
