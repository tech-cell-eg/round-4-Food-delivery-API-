<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalOrders = Order::count();
        $maxPage = ceil($totalOrders / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.orders.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.orders.index', ['page' => 1]);
        }

        $orders = Order::with([
            'customer.user:id,name,email,phone,profile_image',
            'address',
            'coupon',
            'orderItems.dish:id,name,image',
            'payment',
            'statusHistories' => function ($query) {
                $query->latest()->take(1);
            }
        ])
        ->withCount('orderItems')
        ->latest()
        ->paginate($perPage);

        // Calculate statistics
        $stats = [
            'total' => $totalOrders,
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'this_month_orders' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view("dashboard.pages.orders.index", compact("orders", "stats"));
    }


    public function show(Order $order)
    {
        $order->load([
            'customer.user',
            'address',
            'coupon',
            'orderItems.dish.chef.user',
            'payment',
            'statusHistories.user'
        ]);

        return view("dashboard.pages.orders.show", compact("order"));
    }


    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        $order->statusHistories()->create([
            'status' => $request->status,
            'note' => $request->note ?? 'Status updated from admin dashboard',
            'changed_by' => auth()->guard('admin')->id(),
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Order status updated from {$oldStatus} to {$request->status}",
            'status' => $request->status
        ]);
    }



    public function destroy(Order $order)
    {
        try {
            // Check if order can be deleted
            if ($order->status === 'completed') {
                return redirect()->route('admin.orders.index')
                    ->with('error', 'Cannot delete completed orders');
            }

            // Delete related records
            $order->orderItems()->delete();
            $order->statusHistories()->delete();
            if ($order->payment) {
                $order->payment->delete();
            }

            $order->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
