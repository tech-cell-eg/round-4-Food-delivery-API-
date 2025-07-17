<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Chef;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $totalUsers = User::count();
        $totalCustomers = Customer::count();
        $totalChefs = Chef::count();
        $totalAdmins = Admin::count();

        $totalOrders = Order::count();
        $totalDishes = Dish::count();
        $totalCategories = Category::count();
        $totalIngredients = Ingredient::count();
        $totalCoupons = Coupon::count();
        $totalReviews = Review::count();

        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalOrdersValue = Order::where('status', 'completed')->sum('total');
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');

        $totalCarts = Cart::count();
        $activeCarts = Cart::whereHas('items')->count();
        $verifiedChefs = Chef::where('is_verified', true)->count();
        $unverifiedChefs = Chef::where('is_verified', false)->count();

        $recentOrders = Order::where('created_at', '>=', now()->subDays(7))->count();
        $recentRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('amount');

        $averageRating = Review::avg('rating');

        return view("dashboard.pages.index", get_defined_vars());
    }
}
