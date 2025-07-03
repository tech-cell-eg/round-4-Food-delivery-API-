<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض قائمة طلبات المستخدم الحالي
     */
    public function index()
    {
        //$customerId = Auth::user()->customer->id;
        $customerId = 1;
        $orders = Order::with(['orderItems', 'payments'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function show($id)
    {
        //$customerId = Auth::user()->customer->id;
        $customerId = 1;
        $order = Order::with(['orderItems.dish', 'payments', 'address', 'coupon'])
            ->where('customer_id', $customerId)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * إنشاء طلب جديد من سلة التسوق
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,debit_card,cash_on_delivery,wallet',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        //$customerId = Auth::user()->customer->id;
        $customerId = 1;
        $cart = Cart::with(['items.dish'])
            ->where('customer_id', $customerId)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق فارغة'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // حساب المجموع الفرعي
            $subtotal = $cart->items->sum('price');

            // تطبيق الكوبون إذا كان موجودًا
            $discount = 0;
            $couponId = null;
            if ($request->has('coupon_code') && !empty($request->coupon_code)) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where('expiry_date', '>=', now())
                    ->first();

                if ($coupon) {
                    $discount = $coupon->discount_type === 'percentage'
                        ? ($subtotal * $coupon->discount_value / 100)
                        : $coupon->discount_value;
                    $couponId = $coupon->id;
                }
            }

            // حساب رسوم التوصيل والضرائب
            $deliveryFee = 10; // يمكن تغييره حسب المسافة أو قيمة الطلب
            $tax = $subtotal * 0.15; // ضريبة القيمة المضافة 15%

            // حساب المجموع النهائي
            $total = $subtotal + $deliveryFee + $tax - $discount;

            // إنشاء الطلب
            $order = Order::create([
                'customer_id' => $customerId,
                'address_id' => $request->address_id,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'coupon_id' => $couponId,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // إنشاء عناصر الطلب
            foreach ($cart->items as $item) {
                $dish = $item->dish;
                $dishSize = \App\Models\DishSize::where('dish_id', $item->product_id)
                    ->where('price', $item->price)
                    ->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $item->dish_id,
                    'dish_name' => $dish->name,
                    'size_name' => $item->size_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'completed',
                'amount' => $total,
            ]);

            // تفريغ سلة التسوق
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => [
                    'order' => $order->load(['orderItems', 'payments']),
                    'payment' => $payment
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إنشاء الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إلغاء طلب
     */
    public function cancel($id)
    {
        $customerId = Auth::user()->customer->id;
        $order = Order::where('customer_id', $customerId)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن إلغاء هذا الطلب'
            ], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        // تحديث حالة الدفع إذا كان الدفع مسبقًا
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment && $payment->status === 'completed') {
            $payment->status = 'refunded';
            $payment->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم إلغاء الطلب بنجاح',
            'data' => $order
        ]);
    }
}
