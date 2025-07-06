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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/chat-test', function () {
//    return view('chat');
//});
