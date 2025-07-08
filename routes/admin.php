<?php

use App\Http\Controllers\API\Dashboard\AuthenticatedSessionController;
use App\Http\Controllers\API\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get("/", DashboardController::class)->name("admin.dashboard")->middleware("admin_auth");

    Route::controller(AuthenticatedSessionController::class)->prefix("auth")->name("admin.")->group(function () {
        Route::middleware("guest")->group(function () {
            Route::get("login", "login")->name("login");
            Route::post("login", "store")->name("store");
        });

        Route::post("logout", "logout")->name("logout")->middleware("admin_auth");
    });

});

