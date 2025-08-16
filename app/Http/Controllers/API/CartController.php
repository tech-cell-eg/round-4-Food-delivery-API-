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

        $cart = Cart::with(['items.dish', 'items.size'])
            ->where('customer_id', $customerId)
            ->firstOrCreate([
                'customer_id' => $customerId,
                'coupon_id' => null,
                'status' => 'empty'
            ]);

        $total = $this->calculateCartTotal($cart);

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $total
        ], 'تم جلب بيانات السلة بنجاح', 200);
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'dish_id' => 'required|exists:dishes,id',
            'size_name' => 'required|in:small,medium,large',
        ]);

        $customerId = $this->getCustomer()->id;

        $cart = Cart::where('customer_id', $customerId)->firstOrCreate([
            'customer_id' => $customerId,
            'coupon_id' => null,
            'status' => 'empty'
        ]);

        $dish = Dish::find($data["dish_id"]);
        if (!$dish) {
            return ApiResponse::error(['message' => 'هذا الطبق غير متوفر حالياً'], 400);
        }

        $dishSize = DishSize::where(['dish_id' => $data["dish_id"], 'size' => $data["size_name"]])->first();
        if (!$dishSize) {
            return ApiResponse::error(['message' => 'حجم الطبق غير متوفر'], 400);
        }

        $existingCartItem = CartItem::where('cart_id', $cart->id)
            ->where('dish_id', $data["dish_id"])
            ->where('size_id', $dishSize->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += 1;
            $existingCartItem->price = $dishSize->price * $existingCartItem->quantity;
            $existingCartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'dish_id' => $data["dish_id"],
                'size_id' => $dishSize->id,
                'quantity' => 1,
                'price' => $dishSize->price,
            ]);
        }

        // تحديث حالة السلة
        $this->updateCartStatus($cart);

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $this->calculateCartTotal($cart)
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

        $cartItem = CartItem::where('cart_id', $cart->id)->where('dish_id', $id)->first();
        if (!$cartItem) {
            return ApiResponse::error('العنصر غير موجود في سلة التسوق', 404);
        }

        // الحصول على السعر الأساسي من حجم الطبق
        $dishSize = DishSize::find($cartItem->size_id);
        if (!$dishSize) {
            return ApiResponse::error('حجم الطبق غير متوفر', 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'price' => $dishSize->price * $request->quantity,
        ]);

        // تحديث حالة السلة
        $this->updateCartStatus($cart);

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $this->calculateCartTotal($cart)
        ], 'تم تحديث كمية العنصر', 200);
    }

    public function removeItem($id)
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();
        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('dish_id', $id)->first();
        if (!$cartItem) {
            return ApiResponse::error(['message' => 'العنصر غير موجود في سلة التسوق'], 404);
        }

        $cartItem->delete();

        // تحديث حالة السلة
        $this->updateCartStatus($cart);

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $this->calculateCartTotal($cart)
        ], 'تم حذف العنصر من سلة التسوق', 200);
    }

    public function clearCart()
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();

        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        if ($cart->items->isEmpty()) {
            return ApiResponse::success($cart, 'السلة فارغة بالفعل', 200);
        }

        $cart->dropItems();
        return ApiResponse::success([], 'تم تفريغ السلة بنجاح');
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

        $subtotal = $this->calculateCartTotal($cart);
        $discount = $this->calculateDiscount($subtotal, $coupon);
        $total = $subtotal - $discount;

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $total,
            'discount' => $discount,
            'coupon' => $coupon,
        ], 'تم تطبيق الكوبون بنجاح', 200);
    }

    public function removeCoupon()
    {
        $customerId = $this->getCustomer()->id;
        $cart = Cart::where('customer_id', $customerId)->first();

        if (!$cart) {
            return ApiResponse::error('سلة التسوق غير موجودة', 404);
        }

        // إزالة الكوبون فقط دون حذف العناصر
        $cart->update(['coupon_id' => null]);

        return ApiResponse::success([
            'cart' => $cart->load('items.dish', 'items.size'),
            'total' => $this->calculateCartTotal($cart)
        ], 'تم إزالة الكوبون بنجاح', 200);
    }

    /**
     * حساب المجموع الكلي للسلة مع الخصم
     */
    private function calculateCartTotal($cart)
    {
        $subtotal = $cart->items->sum('price');
        
        if ($cart->coupon_id) {
            $coupon = Coupon::find($cart->coupon_id);
            if ($coupon && $coupon->is_active && $coupon->expires_at >= Carbon::now()) {
                $discount = $this->calculateDiscount($subtotal, $coupon);
                return $subtotal - $discount;
            }
        }
        
        return $subtotal;
    }

    /**
     * تحديث حالة السلة بناءً على عدد العناصر
     */
    private function updateCartStatus($cart)
    {
        $itemCount = $cart->items->count();
        
        if ($itemCount === 0) {
            $cart->update(['status' => 'empty']);
        } else {
            $cart->update(['status' => 'filled']);
        }
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
