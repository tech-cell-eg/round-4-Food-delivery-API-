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
use Illuminate\Support\Facades\DB;

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
        $cart = $this->getOrCreateCart($request->customer_id);

        // تحميل العناصر والأطباق والأحجام
        $cart->load(['items.dish.chef', 'items.size']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'data' => [
                    'cart' => $cart,
                    'subtotal' => 0,
                    'discount' => 0,
                    'total' => 0,
                ],
                'message' => 'سلة التسوق فارغة',
            ]);
        }

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
            'customer_id' => 'required|exists:users,id',
            'dish_id' => 'required|exists:dishes,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التحقق من وجود الطبق وأنه متاح
        $dish = Dish::find($request->dish_id);
        if (!$dish) {
            return response()->json([
                'message' => 'هذا الطبق غير متاح حالياً'
            ], 422);
        }

        // التحقق من حجم الطبق إذا تم تحديده
        $dishSize = DishSize::where(['dish_id' => $dish->id, 'name' => $request->size])->first();


        // الحصول على سلة التسوق الحالية للمستخدم أو إنشاء واحدة جديدة
        $cart = $this->getOrCreateCart($request->customer_id);

        // التحقق مما إذا كان العنصر موجوداً بالفعل في السلة
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('dish_id', $dish->id)
            ->where('size', $dishSize->name)
            ->first();

        if ($existingItem) {
            // تحديث الكمية إذا كان العنصر موجوداً بالفعل
            $existingItem->quantity     += $request->quantity;
            $existingItem->price        = $request->price;
            $existingItem->notes        = $request->notes;
            $existingItem->size         = $dishSize->name;
            $existingItem->update();
            $cartItem                   = $existingItem;
        } else {
            // إنشاء عنصر جديد إذا لم يكن موجوداً
            $cartItem = CartItem::create([
                'cart_id'   => $cart->id,
                'dish_id'   => $dish->id,
                'size'      => $dishSize->name,
                'price'     => $request->price ?? $dish->base_price,
                'quantity'  => $request->quantity ?? 1,
                'notes'     => $request->notes ?? 'لا يوجد ملاحظات',
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
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|decimal:2',
            'notes' => 'nullable|string',
            'size' => 'required|string',
        ]);


        // التحقق من وجود العنصر فى قاعدة البيانات
        $cartItem = CartItem::findOrFail($id);

        $cartItem->update($validated);

        return response()->json([
            'data' => $cartItem->load(['dish', 'size']),
            'message' => 'تم تحديث الطلب بنجاح',
        ]);
    }

    /**
     * حذف عنصر من سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request)
    {

        // التحقق من وجود العنصر في قاعدة البيانات
        $cartItem = CartItem::find($request->id);
        if (!$cartItem) {
            return response()->json([
                'message' => 'العنصر غير موجود'
            ], 422);
        }

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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string',
        ]);

        // البحث عن الكوبون
        $coupon = Coupon::where('code', $validated['code'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'كوبون الخصم غير صالح أو منتهي الصلاحية'
            ], 422);
        }

        // الحصول على سلة التسوق الحالية للمستخدم
        $cart = $this->getOrCreateCart($request->user_id);

        // تطبيق الكوبون على السلة

        $cart->update(['coupon_id' => $coupon->id]);

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
        $cart = $this->getOrCreateCart($request->user_id);

        // إزالة الكوبون
        $cart->update(['coupon_id' => null]);

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
        $cart = Cart::where('customer_id', $userId)
            ->where('status', true)
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => $userId,
                'status' => true,
            ]);
        }

        return $cart;
    }
}
