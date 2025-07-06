<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Customer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->type !== 'customer') {
            return redirect()->route('home')->with('error', 'عذراً، هذه الصفحة مخصصة للعملاء فقط.');
        }

        return $next($request);
    }
}
