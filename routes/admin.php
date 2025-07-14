<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\ChefsController;
use App\Http\Controllers\Dashboard\CustomersController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DishesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\IngredientsController;
use App\Http\Controllers\Dashboard\AdminProfileController;
use App\Http\Controllers\Dashboard\OrdersController;
use App\Http\Controllers\Dashboard\PaymentsController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\CouponsController;
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

        Route::resource("categories", CategoriesController::class)->except(["show"]);

        Route::resource("ingredients", IngredientsController::class)->except(["show"]);

        Route::resource("dishes", DishesController::class)->except(["show"]);

        Route::resource("chefs", ChefsController::class)->except(["show"]);

        Route::resource("coupons", CouponsController::class)->except(["show"]);

        Route::post("coupons/{coupon}/toggle-status", [CouponsController::class, 'toggleStatus'])
            ->name('coupons.toggle-status');

        Route::controller(OrdersController::class)->prefix("orders")->name("orders.")->group(function () {
            Route::get("", "index")->name("index");
            Route::get("/{order}", "show")->name("show");
            Route::put("/{order}/status", "updateStatus")->name("updateStatus");
            Route::delete("/{order}", "destroy")->name("destroy");
        });

        Route::controller(PaymentsController::class)->prefix("payments")->name("payments.")->group(function () {
            Route::get("", "index")->name("index");

        });

    });
});
