<?php

namespace App\Http\Controllers\API\Chef;

use App\Models\Chef;
use App\Models\Order;
use App\Models\Payment;
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
        
        $paymentsQuery = Payment::whereHas('order.orderItems.dish', function ($q) use ($chef) {
            $q->where('chef_id', $chef->id);
        })->where('status', 'completed');

        $paymentsQuery = $this->applyDateFilter($paymentsQuery, $period);
        $revenue = (clone $paymentsQuery)->sum('amount');

        $revenueDetails = $this->getRevenueDetails($paymentsQuery, $period);

        return ApiResponse::success([
            'period' => $period,
            'total_orders' => $totalOrders,
            'running_orders' => $runningOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'revenue' => $revenue,
            'revenue_details' => $revenueDetails,
        ]);
    }

    private function getRevenueDetails($paymentsQuery, $period)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                return (clone $paymentsQuery)
                    ->selectRaw('HOUR(created_at) as hour, SUM(amount) as revenue')
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'time' => $item->hour . ':00',
                            'revenue' => $item->revenue
                        ];
                    });

            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                return (clone $paymentsQuery)
                    ->selectRaw('DAY(created_at) as day, SUM(amount) as revenue')
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get()
                    ->map(function ($item) use ($lastMonth) {
                        return [
                            'day' => $lastMonth->year . '-' . $lastMonth->month . '-' . $item->day,
                            'revenue' => $item->revenue
                        ];
                    });

            case 'last_year':
                return (clone $paymentsQuery)
                    ->selectRaw('MONTH(created_at) as month, SUM(amount) as revenue')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) use ($now) {
                        return [
                            'month' => ($now->year - 1) . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                            'revenue' => $item->revenue
                        ];
                    });

            default:
                return [];
        }
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
