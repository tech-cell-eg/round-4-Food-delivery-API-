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

        // Roles management - requires manage_roles permission
        Route::middleware('permission:manage_roles')->group(function () {
            Route::resource("roles", RoleController::class)->except(["show"]);
        });

        // Permissions management - requires manage_permissions permission
        Route::middleware('permission:manage_permissions')->group(function () {
            Route::resource("permissions", PermissionController::class)->except(["show"]);
        });

        // Admins management - requires manage_admins permission
        Route::middleware('permission:manage_admins')->group(function () {
            Route::resource("admins", AdminsController::class)->except(["show"]);
        });

        // Profile management - any authenticated admin can edit their profile
        Route::controller(AdminProfileController::class)->prefix("profile")->name("profile.")->group(function () {
            Route::get("edit", "edit")->name("edit");
            Route::put("update", "update")->name("update");
        });

        // Customers management - requires manage_customers permission
        Route::middleware('permission:manage_customers')->group(function () {
            Route::resource("customers", CustomersController::class)->except(["show"]);
        });

        // Categories management - requires manage_categories permission
        Route::middleware('permission:manage_categories')->group(function () {
            Route::resource("categories", CategoriesController::class)->except(["show"]);
        });

        // Ingredients management - requires manage_ingredients permission
        Route::middleware('permission:manage_ingredients')->group(function () {
            Route::resource("ingredients", IngredientsController::class)->except(["show"]);
        });

        // Dishes management - requires manage_dishes permission
        Route::middleware('permission:manage_dishes')->group(function () {
            Route::resource("dishes", DishesController::class)->except(["show"]);
        });

        // Chefs management - requires manage_chefs permission
        Route::middleware('permission:manage_chefs')->group(function () {
            Route::resource("chefs", ChefsController::class)->except(["show"]);
        });

        // Coupons management - requires manage_coupons permission
        Route::middleware('permission:manage_coupons')->group(function () {
            Route::resource("coupons", CouponsController::class)->except(["show"]);
            Route::post("coupons/{coupon}/toggle-status", [CouponsController::class, 'toggleStatus'])
                ->name('coupons.toggle-status');
        });

        // Orders management - requires manage_orders permission
        Route::middleware('permission:manage_orders')->group(function () {
            Route::controller(OrdersController::class)->prefix("orders")->name("orders.")->group(function () {
                Route::get("", "index")->name("index");
                Route::get("/{order}", "show")->name("show");
                Route::put("/{order}/status", "updateStatus")->name("updateStatus");
                Route::delete("/{order}", "destroy")->name("destroy");
            });
        });

        // Payments view - requires view_payments permission
        Route::middleware('permission:view_payments')->group(function () {
            Route::controller(PaymentsController::class)->prefix("payments")->name("payments.")->group(function () {
                Route::get("", "index")->name("index");
            });
        });

    });
});
