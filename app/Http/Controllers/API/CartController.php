<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Dish;
use App\Models\DishSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * عرض سلة التسوق الحالية للمستخدم
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // الحصول على سلة التسوق الحالية للمستخدم أو إنشاء واحدة جديدة
        $cart = $this->getOrCreateCart($request->user()->id);

        // تحميل العناصر والأطباق والأحجام
        $cart->load(['items.dish.chef', 'items.size']);

        // حساب المجموع الكلي
        $subtotal = $cart->items->sum(function ($item) {
            $price = $item->dish->base_price;
            if ($item->size) {
                $price *= $item->size->price_multiplier;
            }
            return $price * $item->quantity;
        });

        // حساب الخصم إذا كان هناك كوبون
        $discount = 0;
        if ($cart->coupon_id) {
            $coupon = Coupon::find($cart->coupon_id);
            if ($coupon) {
                if ($coupon->discount_type === 'percentage') {
                    $discount = $subtotal * ($coupon->discount_value / 100);
                } else {
                    $discount = $coupon->discount_value;
                }
            }
        }

        // حساب المجموع النهائي
        $total = $subtotal - $discount;

        return response()->json([
            'data' => [
                'cart' => $cart,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
            ],
            'message' => 'تم جلب سلة التسوق بنجاح',
        ]);
    }

    /**
     * إضافة عنصر إلى سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dish_id' => 'required|exists:dishes,id',
            'size' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التحقق من وجود الطبق وأنه متاح
        $dish = Dish::findOrFail($request->dish_id);
        if (!$dish->is_available) {
            return response()->json([
                'message' => 'هذا الطبق غير متاح حالياً'
            ], 422);
        }

        // التحقق من حجم الطبق إذا تم تحديده
        $sizeId = null;
        if ($request->has('size') && $request->size) {
            $size = DishSize::where('dish_id', $dish->id)
                ->where('name', $request->size)
                ->first();

            if (!$size) {
                return response()->json([
                    'message' => 'حجم الطبق غير متاح'
                ], 422);
            }

            $sizeId = $size->id;
        }

        // الحصول على سلة التسوق الحالية للمستخدم أو إنشاء واحدة جديدة
        $cart = $this->getOrCreateCart($request->user()->id);

        // التحقق مما إذا كان العنصر موجوداً بالفعل في السلة
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('dish_id', $dish->id)
            ->where('size_id', $sizeId)
            ->first();

        if ($existingItem) {
            // تحديث الكمية إذا كان العنصر موجوداً بالفعل
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
            $cartItem = $existingItem;
        } else {
            // إنشاء عنصر جديد إذا لم يكن موجوداً
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'dish_id' => $dish->id,
                'size_id' => $sizeId,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'data' => $cartItem->load(['dish', 'size']),
            'message' => 'تم إضافة العنصر إلى سلة التسوق بنجاح',
        ], 201);
    }

    /**
     * تحديث كمية عنصر في سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // الحصول على سلة التسوق الحالية للمستخدم
        $cart = $this->getOrCreateCart($request->user()->id);

        // التحقق من وجود العنصر في سلة التسوق
        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        // تحديث الكمية
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'data' => $cartItem->load(['dish', 'size']),
            'message' => 'تم تحديث الكمية بنجاح',
        ]);
    }

    /**
     * حذف عنصر من سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request, $id)
    {
        // الحصول على سلة التسوق الحالية للمستخدم
        $cart = $this->getOrCreateCart($request->user()->id);

        // التحقق من وجود العنصر في سلة التسوق
        $cartItem = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        // حذف العنصر
        $cartItem->delete();

        return response()->json([
            'message' => 'تم حذف العنصر من سلة التسوق بنجاح',
        ]);
    }

    /**
     * تطبيق كوبون خصم على سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // البحث عن الكوبون
        $coupon = Coupon::where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'كوبون الخصم غير صالح أو منتهي الصلاحية'
            ], 422);
        }

        // الحصول على سلة التسوق الحالية للمستخدم
        $cart = $this->getOrCreateCart($request->user()->id);

        // تطبيق الكوبون على السلة
        $cart->coupon_id = $coupon->id;
        $cart->save();

        // إعادة تحميل السلة مع العناصر والكوبون
        $cart->load(['items.dish.chef', 'items.size', 'coupon']);

        return response()->json([
            'data' => $cart,
            'message' => 'تم تطبيق كوبون الخصم بنجاح',
        ]);
    }

    /**
     * إزالة كوبون الخصم من سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeCoupon(Request $request)
    {
        // الحصول على سلة التسوق الحالية للمستخدم
        $cart = $this->getOrCreateCart($request->user()->id);

        // إزالة الكوبون
        $cart->coupon_id = null;
        $cart->save();

        return response()->json([
            'message' => 'تم إزالة كوبون الخصم بنجاح',
        ]);
    }

    /**
     * الحصول على سلة التسوق الحالية للمستخدم أو إنشاء واحدة جديدة
     *
     * @param  int  $userId
     * @return \App\Models\Cart
     */
    private function getOrCreateCart($userId)
    {
        $cart = Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'status' => 'active',
            ]);
        }

        return $cart;
    }
}
