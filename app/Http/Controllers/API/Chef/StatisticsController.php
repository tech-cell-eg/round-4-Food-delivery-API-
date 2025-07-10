<?php

namespace App\Http\Controllers\API\Chef;

use App\Models\Chef;
use App\Models\Order;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function statistics(Request $request)
    {
        $chef = Auth::user()->chef;

        if (!$chef) {
            return ApiResponse::unauthorized();
        }

        $period = $request->input('period', 'all');

        $ordersQuery = Order::whereHas('orderItems.dish', function ($q) use ($chef) {
            $q->where('chef_id', $chef->id);
        });

        $ordersQuery = $this->applyDateFilter($ordersQuery, $period);

        $totalOrders = (clone $ordersQuery)->count();
        $runningOrders = (clone $ordersQuery)->whereIn('status', ['processing', 'on_the_way'])->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $revenue = (clone $ordersQuery)->where('status', 'delivered')->sum('total');

        return ApiResponse::success([
            'period' => $period,
            'total_orders' => $totalOrders,
            'running_orders' => $runningOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'revenue' => $revenue,
        ]);
    }


    private function applyDateFilter($query, $period)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                return $query->whereDate('created_at', $now->toDateString());

            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                return $query->whereMonth('created_at', $lastMonth->month)
                           ->whereYear('created_at', $lastMonth->year);

            case 'last_year':
                return $query->whereYear('created_at', $now->year - 1);

            default:
                return $query;
        }
    }
}
