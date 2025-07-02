<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * تطبيق كوبون خصم على السلة
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'code' => 'required|string|exists:coupons,code'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cart = Cart::with('items.dish', 'items.size')->findOrFail($request->cart_id);
        $coupon = Coupon::where('code', $request->code)->first();

        // التحقق من صلاحية الكوبون
        if (!$this->isCouponValid($coupon)) {
            return response()->json([
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
            ], 400);
        }

        // حساب الخصم
        $subtotal = $cart->items->sum(function ($item) {
            $price = $item->dish->base_price;
            if ($item->size && $item->size->price_multiplier) {
                $price *= $item->size->price_multiplier;
            }
            return $price * $item->quantity;
        });

        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        // تحديث السلة بالخصم
        $cart->update([
            'coupon_id' => $coupon->id,
            'discount' => $discount,
            'total' => $total
        ]);

        return response()->json([
            'message' => 'تم تطبيق كود الخصم بنجاح',
            'coupon' => $coupon->only(['code', 'discount_type', 'discount_value']),
            'discount' => $discount,
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }

    /**
     * إزالة كوبون الخصم من السلة
     *
     * @param  int  $cartId
     * @return \Illuminate\Http\Response
     */
    public function remove($cartId)
    {
        $cart = Cart::findOrFail($cartId);
        
        if (!$cart->coupon_id) {
            return response()->json([
                'message' => 'لا يوجد كوبون خصم مطبق على هذه السلة'
            ], 400);
        }

        $cart->update([
            'coupon_id' => null,
            'discount' => 0,
            'total' => $cart->items->sum(function ($item) {
                $price = $item->dish->base_price;
                if ($item->size && $item->size->price_multiplier) {
                    $price *= $item->size->price_multiplier;
                }
                return $price * $item->quantity;
            })
        ]);

        return response()->json([
            'message' => 'تم إزالة كود الخصم بنجاح',
            'total' => $cart->total
        ]);
    }

    /**
     * التحقق من صلاحية الكوبون
     */
    private function isCouponValid($coupon)
    {
        $now = Carbon::now();
        
        // التحقق من تاريخ الصلاحية
        if ($coupon->valid_from && $coupon->valid_from->gt($now)) {
            return false;
        }

        if ($coupon->valid_until && $coupon->valid_until->lt($now)) {
            return false;
        }

        // التحقق من عدد مرات الاستخدام
        if ($coupon->max_uses && $coupon->uses >= $coupon->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * حساب قيمة الخصم
     */
    private function calculateDiscount($coupon, $subtotal)
    {
        if ($coupon->discount_type === 'percentage') {
            $discount = $subtotal * ($coupon->discount_value / 100);
            
            // التحقق من الحد الأقصى للخصم إذا كان محدداً
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                return $coupon->max_discount;
            }
            
            return $discount;
        }
        
        // خصم بقيمة ثابتة
        return min($coupon->discount_value, $subtotal);
    }
}
