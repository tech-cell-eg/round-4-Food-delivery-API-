<?php

namespace App\Http\Controllers\Api\Chef;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Chef;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function runningOrders(Request $request)
    {
        // $chef = Auth::user()->chef;
        $chef = Chef::first();

        if (!$chef) {
            return ApiResponse::unauthorized();
        }

        $orders = Order::whereIn('status', ['processing', 'on_the_way'])
            ->whereHas('orderItems.dish', function ($q) use ($chef) {
                $q->where('chef_id', $chef->id);
            })
            ->with(['orderItems.dish'])
            ->paginate(5);
        return ApiResponse::withPagination($orders);
    }


    public function markAsDone(Request $request, $orderId)
    {
        // $chef = Auth::user()->chef;
        $chef = Chef::first();

        if (!$chef) {
            return ApiResponse::unauthorized();
        }
        $order = Order::where('id', $orderId)
            ->whereHas('orderItems.dish', function ($q) use ($chef) {
                $q->where('chef_id', $chef->id);
            })
            ->first();
        if (!$order) {
            return ApiResponse::notFound("Order does not exist or does not concern you.");
        }
        $order->status = 'delivered';
        $order->save();

        return ApiResponse::success([], "Order status has been updated to Complete.");
    }


    public function cancelOrder(Request $request, $orderId)
    {
        // $chef = Auth::user()->chef;
        $chef = Chef::first();

        if (!$chef) {
            return ApiResponse::unauthorized();
        }

        $order = Order::where('id', $orderId)
            ->whereHas('orderItems.dish', function ($q) use ($chef) {
                $q->where('chef_id', $chef->id);
            })
            ->first();
        if (!$order) {
            return ApiResponse::notFound("Order does not exist or does not concern you.");
        }
        $order->status = 'cancelled';
        $order->save();

        return ApiResponse::success([], "Order was successfully cancelled.");
    }
}
