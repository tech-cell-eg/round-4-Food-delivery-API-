<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdatesController extends Controller
{
    //
    public function cart()
    {

        $cart = Cart::where('customer_id', Auth::user()->id)->with('items', 'coupon')->first();
        return response()->json([
            'cart' => $cart,
            'items' => $cart->items,
        ]);
    }
}
