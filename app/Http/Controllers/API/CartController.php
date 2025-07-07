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
use App\Helpers\ApiResponse;

class CartController extends Controller
{
    /**
     * Get the authenticated customer or return error response
     */
    private function getCustomer()
    {
        $customer = Auth::user()->customer;
        if (!$customer) {
            abort(response()->json([
                'status' => false,
                'message' => 'المستخدم الحالي ليس لديه حساب كـ عميل',
                'data' => null
            ], 403));
        }
        return $customer;
    }

    public function index()
    {
        $customerId = $this->getCustomer()->id;

        $cart = Cart::with(['items.dish'])
            ->where('customer_id', $customerId)
            ->firstOrCreate([
                'customer_id' => $customerId,
                'coupon_id' => null,
                'status' => 'empty'
            ]);

        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return ApiResponse::success([
            'cart' => $cart->load('items.dish'),
            'items' => $cart->items->load(['dish', 'size']),
            'total' => $total
        ], 'تم جلب بيانات السلة بنجاح', 200);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'size_name' => 'required|in:small,medium,large',
        ]);

        $customerId = $this->getCustomer()->id;

        $cart = Cart::where('customer_id', $customerId)->firstOrCreate([
            'customer_id' => $customerId,
            'coupon_id' => null,
            'status' => 'empty'
        ]);

        $dish = Dish::find($request->dish_id);
        if (!$dish) {
            return ApiResponse::error(['message' => 'هذا الطبق غير متوفر حالياً'], 400);
        }

        $dishSize = DishSize::where(['dish_id' => $request->dish_id, 'size' => $request->size_name])->first();
        if (!$dishSize) {
            return ApiResponse::error(['message' => 'حجم الطبق غير متوفر'], 400);
        }

        $existCartItem = CartItem::where('cart_id', $cart->id)
            ->where('dish_id', $request->dish_id)
            ->first();

        if ($existCartItem) {
            $existCartItem->quantity += 1;
            $existCartItem->price = $dishSize->price * $existCartItem->quantity;
            $existCartItem->update();
        } else {
            CartItem::create([
                'customer_id' => $customerId,
                'size_id' => $dishSize->id,
                'cart_id' => $cart->id,
                'dish_id' => $request->dish_id,
                'quantity' => 1,
                'price' => $dishSize->price,
            ]);
        }

        return ApiResponse::success([
            'cart' => $cart->load('items.dish'),
            'items' => $cart->items->load(['dish', 'size']),
            'total' => $cart->items->sum('price')
        ], 'تمت إضافة العنصر إلى سلة التسوق', 200);
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) {
            return ApiResponse::error('العنصر غير موجود في سلة التسوق', 404);
        }

        $unitPrice = $cartItem->price / $cartItem->quantity;
        $cartItem->update([
            'quantity' => $request->quantity,
            'price' => $unitPrice * $request->quantity,
        ]);

        return ApiResponse::success([
            'cart' => $cart->load('items')
        ], 'تم تحديث كمية العنصر', 200);
    }

    public function removeItem($id)
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) {
            return ApiResponse::error(['message' => 'العنصر غير موجود في سلة التسوق'], 404);
        }

        $cartItem->delete();

        return ApiResponse::success([
            'cart' => $cart->load('items.dish'),
            'items' => $cart->items->load(['dish', 'size']),
            'total' => $cart->items->sum('price')
        ], 'تم حذف العنصر من سلة التسوق', 200);
    }

    public function clearCart()
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();

        if ($cart && $cart->items->isEmpty()) {
            return ApiResponse::success(['cart' => $cart, 'items' => []], 'لم نجد لديك أطباق فى السلة', 200);
        }

        if ($cart) {
            $cart->dropItems();
            return ApiResponse::success(['cart' => $cart, 'items' => []], 'تم تفريغ سلة التسوق', 200);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code'
        ]);

        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        if ($cart->items->isEmpty()) {
            return ApiResponse::error('سلة التسوق فارغة', 400);
        }

        $coupon = Coupon::where(['code' => $request->code, 'is_active' => true])
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if (!$coupon) {
            return ApiResponse::error('كود الخصم غير صالح أو منتهي الصلاحية', 400);
        }

        if ($cart->coupon_id === $coupon->id) {
            return ApiResponse::error('تم تطبيق هذا الكوبون مسبقاً', 400);
        }

        $cart->update(['coupon_id' => $coupon->id]);

        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = $this->calculateDiscount($subtotal, $coupon);
        $total = $subtotal - $discount;

        return ApiResponse::success([
            'cart' => $cart,
            'total' => $total,
            'discount' => $discount,
            'coupon' => $coupon,
        ], 'تم تطبيق الكوبون بنجاح', 200);
    }

    public function removeCoupon()
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();

        if ($cart) {
            foreach ($cart->items as $item) {
                $item->delete();
            }
            $cart->coupon_id = null;
            $cart->save();
            $cart->delete();
        }

        return ApiResponse::success([], 'تم تفريغ سلة التسوق', 200);
    }

    private function calculateDiscount($amount, $coupon)
    {
        if ($coupon->discount_type === 'fixed') {
            return min($coupon->discount_value, $amount);
        } else {
            return $amount * ($coupon->discount_value / 100);
        }
    }
}
