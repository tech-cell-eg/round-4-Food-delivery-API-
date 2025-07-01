<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Chef\ChefController;
use App\Http\Controllers\Api\Chef\DishController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ChefController::class)->group(function () {
    Route::get('/open-resturants', 'getOpenChefs')->name("getOpenChefs");
    Route::get('/resturants/{id}', 'showChefWithCategoriesAndMeals')->name("showChefWithCategoriesAndMeals");
    
});

Route::get("categories/", [CategoryController::class, "index"])->name("categories.index");

Route::controller(DishController::class)->prefix("meals")->name("meals.")/*->middleware("auth:sanctum")*/->group(function () {
    Route::get('/', 'index')->name("index");
    Route::get('/{id}', 'show')->name("show");
    Route::post('/', 'store')->name("store");

});
