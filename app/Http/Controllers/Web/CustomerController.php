<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('Chef'); // سنقوم بإنشاء هذا الميدلوير لاحقاً
    }

    public function dashboard()
    {
        $user = Auth::user();
        return $user;
        $restaurant = $user->restaurant; // نفترض وجود علاقة بين المستخدم والمطعم

        $stats = [
            'total_meals' => $user->meals()->count(),
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'total_earnings' => $user->orders()->where('status', 'completed')->sum('total_amount'),
        ];

        return view('dashboard', compact('stats'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with(['items.meal', 'user'])
            ->latest()
            ->paginate(10);

        return view('chef.orders.index', compact('orders'));
    }
}
