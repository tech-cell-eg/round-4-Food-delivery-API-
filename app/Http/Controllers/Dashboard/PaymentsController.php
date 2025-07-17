<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalPayments = Payment::count();
        $maxPage = ceil($totalPayments / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.payments.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.payments.index', ['page' => 1]);
        }

        // جلب المدفوعات مع العلاقات المطلوبة
        $payments = Payment::with([
            'order.customer.user:id,name,email,phone',
            'order.chef.user:id,name,email'
        ])
        ->latest()
        ->paginate($perPage);

        // حساب الإحصائيات
        $stats = [
            'total' => $totalPayments,
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'refunded' => Payment::where('status', 'refunded')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'today_payments' => Payment::whereDate('created_at', today())->count(),
            'this_month_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'credit_card_payments' => Payment::where('payment_method', 'credit_card')->count(),
            'cash_payments' => Payment::where('payment_method', 'cash_on_delivery')->count(),
        ];

        return view('dashboard.pages.payments.index', compact('payments', 'stats'));
    }
}
