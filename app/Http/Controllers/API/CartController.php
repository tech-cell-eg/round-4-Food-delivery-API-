<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Dish;
use App\Models\DishSize;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * عرض محتويات سلة التسوق للمستخدم الحالي
     */
    public function index()
    {
        //$customerId = Auth::user()->customer->id;
        $customerId = 1;

        $cart = Cart::with(['items.dish'])
            ->where('customer_id', $customerId)
            ->first();

        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'cart' => null,
                    'items' => [],
                    'total' => 0
                ]
            ], 200);
        }

        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'cart' => $cart,
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
            'size_name' => 'required|in:small,medium,large',
            'quantity' => 'required|integer|min:1',
        ]);

        //$customerId = Auth::user()->customer->id;
        $customerId = 1;

        // التحقق من وجود سلة تسوق للعميل
        $cart = Cart::firstOrCreate(['customer_id' => $customerId]);

        // التحقق من وجود الطبق والحجم المطلوب
        $dish = Dish::find($request->dish_id);

        if (!$dish) {
            return response()->json([
                'status' => 'error',
                'message' => 'هذا الطبق غير متوفر حالياً'
            ], 400);
        }

        // الحصول على سعر الطبق حسب الحجم
        $dishSize = DishSize::where('dish_id', $request->dish_id)
            ->where('size', $request->size_name)
            ->first();

        if (!$dishSize) {
            return response()->json([
                'status' => 'error',
                'message' => 'حجم الطبق غير متوفر'
            ], 400);
        }

        // التحقق مما إذا كان العنصر موجودًا بالفعل في السلة
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('dish_id', $request->dish_id)
            ->first();

        if ($cartItem) {
            // تحديث الكمية إذا كان العنصر موجودًا بالفعل
            $cartItem->quantity += $request->quantity;
            $cartItem->price = $dishSize->price ?? 100;
            $cartItem->save();
        } else {
            // إنشاء عنصر جديد في السلة
            $cartItem = new CartItem([
                'customer_id' => $customerId,
                'size_name' => $request->size_name,
                'cart_id' => $cart->id,
                'dish_id' => $request->dish_id,
                'quantity' => $request->quantity,
                'price' => $dishSize->price ?? 100,
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

        // $customerId = Auth::user()->customer->id;
        $customerId = 1;
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
        // $customerId = Auth::user()->customer->id;
        $customerId = 1;
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
        // $customerId = Auth::user()->customer->id;
        $customerId = 1;
        $cart = Cart::where('customer_id', $customerId)->first();

        if ($cart) {
            foreach ($cart->items as $item) {
                $item->delete();
            }
            $cart->coupon_id = null;
            $cart->save();
            $cart->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم تفريغ سلة التسوق'
        ]);
    }

    /**
     * تطبيق كوبون خصم على سلة التسوق
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code'
        ]);

        // $customerId = Auth::user()->customer->id;
        $customerId = 1;
        $cart = Cart::where('customer_id', $customerId)->first();

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق غير موجودة'
            ], 404);
        }

        // Check if cart has items
        if ($cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق فارغة'
            ], 400);
        }

        // Find the coupon
        $coupon = Coupon::where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
            ], 400);
        }

        // Check if coupon is already applied
        if ($cart->coupon_id === $coupon->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'تم تطبيق هذا الكوبون مسبقاً'
            ], 400);
        }

        // Apply coupon to cart
        $cart->coupon_id = $coupon->id;
        $cart->save();

        // Calculate new total with discount
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = $this->calculateDiscount($subtotal, $coupon);
        $total = $subtotal - $discount;

        return response()->json([
            'status' => 'success',
            'message' => 'تم تطبيق كود الخصم بنجاح',
            'data' => [
                'coupon' => $coupon,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total
            ]
        ]);
    }

    /**
     * إزالة كوبون الخصم من سلة التسوق
     */
    public function removeCoupon()
    {
        // $customerId = Auth::user()->customer->id;
        $customerId = 1; // Replace with Auth::user()->customer->id
        $cart = Cart::where('customer_id', $customerId)->first();

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق غير موجودة'
            ], 404);
        }

        if (!$cart->coupon_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد كوبون مطبق حالياً'
            ], 400);
        }

        $cart->coupon_id = null;
        $cart->save();

        // Recalculate total without discount
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'تم إزالة كود الخصم بنجاح',
            'data' => [
                'subtotal' => $subtotal,
                'total' => $subtotal
            ]
        ]);
    }

    /**
     * حساب قيمة الخصم بناءً على نوع القسيمة
     */
    private function calculateDiscount($amount, $coupon)
    {
        if ($coupon->discount_type === 'fixed') {
            return min($coupon->discount_value, $amount);
        } else {
            return $amount * ($coupon->discount_value / 100);
        }
    }
}
