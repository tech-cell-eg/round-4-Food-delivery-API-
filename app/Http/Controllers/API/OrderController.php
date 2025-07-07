<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Notifications\CustomerActionNotification;
use App\Models\User;
use App\Helpers\ApiResponse;
use App\Models\OrderStatusHistory;

class OrderController extends Controller
{
    /**
     * سجل تتبع حالة الطلب
     */
    protected function logOrderStatus($order, $status, $note = null)
    {
        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $status,
            'note'       => $note,
            'changed_by' => Auth::id(),
            'created_at' => now(),
        ]);
    }
    /**
     * عرض قائمة طلبات المستخدم الحالي
     */
    public function index()
    {
        $customerId = Auth::user()->customer->id;
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
        $customerId = Auth::user()->customer->id;

        $order = Order::find($id);

        if (!$order) {
            return ApiResponse::success([
                'message' => 'لم يتم العثور على الطلب'
            ], 200);
        }

        if ($customerId != $order->customer_id) {
            return ApiResponse::error([
                'message' => 'هذا الطلب يخص شخص آخر، لا يمكننا عرض البيانات'
            ], 403);
        }

        return ApiResponse::success([
            'order' => $order
        ], 'تم جلب بيانات الطلب بنجاح', 200);
    }

    /**
     * إنشاء طلب جديد من سلة التسوق
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customerId = Auth::user()->customer->id;
        $cart = Cart::with(['items.dish'])
            ->where('customer_id', $customerId)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'سلة التسوق فارغة'
            ], 400);
        }

        $restaurantId = $cart->items->first()->dish->chef->id;
        if (!$restaurantId) {
            return response()->json([
                'status' => 'error',
                'message' => 'برجاء تحديد المطعم'
            ], 400);
        }
        //return $restaurantId;
        DB::beginTransaction();
        try {
            // حساب المجموع الفرعي
            $subtotal = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // تطبيق الخصم إذا كان هناك كوبون
            $discount = 0;
            $couponId = null;

            if ($request->has('coupon_code') && !empty($request->coupon_code)) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where('expires_at', '>=', now())
                    ->first();

                if ($coupon) {
                    $discount = $coupon->discount_type === 'fixed'
                        ? $coupon->discount_value
                        : ($subtotal * $coupon->discount_value / 100);

                    $couponId = $coupon->id;
                }
            }

            // حساب رسوم التوصيل والضريبة
            $deliveryFee = 10; // يمكن جعلها ديناميكية
            $tax = $subtotal * 0.15; // 15% ضريبة

            // حساب المجموع النهائي
            $total = $subtotal + $deliveryFee + $tax - $discount;

            // إنشاء الطلب
            $order = Order::create([
                'chef_id' => $restaurantId,
                'customer_id' => $customerId,
                'address_id' => $request->address_id,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'coupon_id' => $couponId,
                'status' => 'pending',
                'order_number' => Order::genNumber(),
                'notes' => $request->notes,
            ]);
            // سجل أول حالة للطلب
            $this->logOrderStatus($order, 'pending', 'تم إنشاء الطلب');

            // إضافة عناصر الطلب
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'chef_id' => $restaurantId,
                    'dish_id' => $item->dish_id,
                    'size' => $item->size_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            // تفريغ سلة التسوق
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => [
                    'order' => $order->load('orderItems')
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
        $customer = Auth::user()->customer;
        $order = Order::where('customer_id', $customer->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return ApiResponse::error([
                'message' => 'لم يتم العثور على الطلب'
            ], 400);
        }

        $order->status = 'cancelled';
        $order->update();
        $this->logOrderStatus($order, 'cancelled', 'تم إلغاء الطلب بواسطة العميل');

        // تحديث حالة الدفع إذا كان الدفع مسبقًا
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment && $payment->status === 'completed') {
            $payment->status = 'refunded';
            $payment->update();
        }
        // Notify related chefs
        $chefIds = $order->orderItems->pluck('dish.chef_id')->unique()->filter();
        foreach ($chefIds as $chefId) {
            $chef = User::find($chefId);
            if ($chef) {
                $chef->notify(new CustomerActionNotification([
                    'title' => 'Order Cancelled',
                    'message' => "{$customer->user->name} cancelled their order.",
                    'image' => $customer->user->profile_photo ?? null . urlencode($customer->user->name),
                    'time' => now()->diffForHumans(),
                ]));
            }
        }

        return ApiResponse::success([
            'order' => $order->load('orderItems')
        ], 'تم إلغاء الطلب بنجاح', 200);
    }

    public function trackOrder($id)
    {
        $customerId = Auth::user()->customer->id;
        $order = Order::where('customer_id', $customerId)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return ApiResponse::error([
                'message' => 'لم يتم العثور على الطلب'
            ], 400);
        }

        return ApiResponse::success([
            'order' => $order->load(['orderItems', 'statusHistories'])
        ], 'تم جلب بيانات الطلب بنجاح', 200);
    }
}
