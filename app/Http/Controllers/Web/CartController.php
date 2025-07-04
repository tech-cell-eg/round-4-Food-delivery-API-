<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Dish;
use App\Models\DishSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display current user's cart.
     */
    public function index()
    {
        $userId = Auth::id();

        $cart = Cart::with(['items.dish'])->where('customer_id', $userId)->first();
        $total = $cart ? $cart->items->sum(fn($item) => $item->price * $item->quantity) : 0;

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add item to cart via POST /cart/items .
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'dish_id'   => 'required|exists:dishes,id',
            'size_name' => 'required|in:small,medium,large',
        ]);

        $userId = Auth::id();

        $cart = Cart::firstOrCreate(['customer_id' => $userId]);

        // Get size price
        $dishSize = DishSize::where('dish_id', $request->dish_id)
            ->where('size', $request->size_name)
            ->firstOrFail();

        $cartItem = CartItem::firstOrCreate(
            [
                'cart_id' => $cart->id,
                'dish_id' => $request->dish_id,
                'size_name' => $request->size_name,
            ],
            [
                'customer_id' => $userId,
                'price' => $dishSize->price,
                'quantity' => 0,
            ]
        );

        $cartItem->increment('quantity');

        return back()->with('success', 'تم إضافة الوجبة إلى السلة');
    }

    /**
     * Update quantity via PATCH /cart/items/{id}
     */
    public function updateItem(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::findOrFail($id);
        $this->authorizeItem($cartItem);

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'تم تحديث الكمية');
    }

    /**
     * Remove item via DELETE /cart/items/{id}
     */
    public function removeItem($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $this->authorizeItem($cartItem);
        $cartItem->delete();

        return back()->with('success', 'تم حذف العنصر من السلة');
    }

    /**
     * Clear entire cart.
     */
    public function clear()
    {
        $userId = Auth::id();
        $cart = Cart::where('customer_id', $userId)->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return back()->with('success', 'تم تفريغ السلة');
    }

    private function authorizeItem(CartItem $item)
    {
        if ($item->customer_id !== Auth::id()) {
            abort(403);
        }
    }
}
