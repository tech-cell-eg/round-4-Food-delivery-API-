<?php

namespace App\Http\Controllers\API;

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
     * عرض محتويات سلة التسوق للمستخدم الحالي
     */
    public function index()
    {
        $customerId = Auth::user()->customer->id;
        $cart = Cart::with(['cartItems.dish.dishSizes'])
                    ->where('customer_id', $customerId)
                    ->first();

        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => [],
                    'total' => 0
                ]
            ]);
        }

        $total = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $cart->cartItems,
                'total' => $total
            ]
        ]);
    }

    /**
     * إضافة عنصر إلى سلة التسوق
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'size' => 'required|in:small,medium,large',
            'quantity' => 'required|integer|min:1',
        ]);

        $customerId = Auth::user()->customer->id;
        
        // التحقق من وجود سلة تسوق للعميل
        $cart = Cart::firstOrCreate(['customer_id' => $customerId]);
        
        // التحقق من وجود الطبق والحجم المطلوب
        $dish = Dish::findOrFail($request->dish_id);
        
        if (!$dish->is_available) {
            return response()->json([
                'status' => 'error',
                'message' => 'هذا الطبق غير متوفر حالياً'
            ], 400);
        }
        
        // الحصول على سعر الطبق حسب الحجم
        $dishSize = DishSize::where('dish_id', $request->dish_id)
                            ->where('size', $request->size)
                            ->first();
        
        if (!$dishSize) {
            return response()->json([
                'status' => 'error',
                'message' => 'حجم الطبق غير متوفر'
            ], 400);
        }
        
        // التحقق مما إذا كان العنصر موجودًا بالفعل في السلة
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $request->dish_id)
                            ->first();
        
        if ($cartItem) {
            // تحديث الكمية إذا كان العنصر موجودًا بالفعل
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // إنشاء عنصر جديد في السلة
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $request->dish_id,
                'quantity' => $request->quantity,
                'price' => $dishSize->price,
            ]);
            $cartItem->save();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة العنصر إلى سلة التسوق',
            'data' => $cartItem
        ]);
    }

    /**
     * تحديث كمية عنصر في سلة التسوق
     */
    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customerId = Auth::user()->customer->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق غير موجودة'
            ], 404);
        }
        
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('id', $id)
                            ->first();
        
        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'العنصر غير موجود في سلة التسوق'
            ], 404);
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث كمية العنصر',
            'data' => $cartItem
        ]);
    }

    /**
     * حذف عنصر من سلة التسوق
     */
    public function removeItem($id)
    {
        $customerId = Auth::user()->customer->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق غير موجودة'
            ], 404);
        }
        
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('id', $id)
                            ->first();
        
        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'العنصر غير موجود في سلة التسوق'
            ], 404);
        }
        
        $cartItem->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف العنصر من سلة التسوق'
        ]);
    }

    /**
     * تفريغ سلة التسوق
     */
    public function clearCart()
    {
        $customerId = Auth::user()->customer->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'تم تفريغ سلة التسوق'
        ]);
    }
}
