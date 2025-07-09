<?php

use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\CustomersController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AdminProfileController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::controller(AuthenticatedSessionController::class)->prefix("auth")->name("admin.")->group(function () {
        Route::middleware("guest")->group(function () {
            Route::get("login", "login")->name("login");
            Route::post("login", "store")->name("store");
        });

        Route::post("logout", "logout")->name("logout")->middleware("admin_auth");
    });

    Route::middleware("admin_auth")->name("admin.")->group(function () {
        Route::get("/", DashboardController::class)->name("dashboard");

        Route::resource("roles", RoleController::class)->except(["show"]);

        Route::resource("permissions", PermissionController::class)->except(["show"]);

        Route::resource("admins", AdminsController::class)->except(["show"]);

        Route::controller(AdminProfileController::class)->prefix("profile")->name("profile.")->group(function () {
            Route::get("edit", "edit")->name("edit");
            Route::put("update", "update")->name("update");
        });

        Route::resource("customers", CustomersController::class)->except(["show"]);

    });


});
