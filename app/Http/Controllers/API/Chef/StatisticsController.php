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

        $ordersQuery = Order::where('chef_id', $chef->id);

        $ordersQuery = $this->applyDateFilter($ordersQuery, $period);

        $totalOrders = (clone $ordersQuery)->count();
        $runningOrders = (clone $ordersQuery)->whereIn('status', ['processing', 'on_the_way'])->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $ordersQuery)->where('status', 'cancelled')->count();

        $paymentsQuery = Payment::whereHas('order', function ($q) use ($chef) {
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
                return (clone $paymentsQuery)
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'date' => $item->date,
                            'revenue' => $item->revenue
                        ];
                    });

            case 'last_year':
                return (clone $paymentsQuery)
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as revenue')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'month' => $item->month,
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
                return $query->whereBetween('created_at', [
                    $now->copy()->subDays(30)->startOfDay(),
                    $now->copy()->endOfDay()
                ]);

            case 'last_year':
                return $query->whereBetween('created_at', [
                    $now->copy()->subYear()->startOfDay(),
                    $now->copy()->endOfDay()
                ]);

            default:
                return $query;
        }
    }
}

