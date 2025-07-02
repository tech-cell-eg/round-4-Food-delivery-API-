<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * عرض قائمة طلبات المستخدم
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::where('customer_id', $request->user_id)
            ->with(['items.dish', 'address'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $orders,
            'message' => 'تم جلب الطلبات بنجاح',
        ]);
    }

    /**
     * إنشاء طلب جديد من محتويات السلة
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cash,card',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $cart = Cart::with(['items.dish', 'items.size', 'coupon'])->findOrFail($request->cart_id);
            
            // التحقق من أن السلة غير فارغة
            if ($cart->items->isEmpty()) {
                return response()->json([
                    'message' => 'لا يمكن إنشاء طلب من سلة فارغة'
                ], 400);
            }

            // حساب التكلفة الإجمالية
            $subtotal = $cart->items->sum(function ($item) {
                $price = $item->dish->base_price;
                if ($item->size && $item->size->price_multiplier) {
                    $price *= $item->size->price_multiplier;
                }
                return $price * $item->quantity;
            });

            $discount = $cart->discount ?? 0;
            $total = max(0, $subtotal - $discount);

            // إنشاء الطلب
            $order = Order::create([
                'customer_id' => $cart->customer_id,
                'address_id' => $request->address_id,
                'coupon_id' => $cart->coupon_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'unpaid',
                'notes' => $request->notes
            ]);

            // إضافة العناصر إلى الطلب
            foreach ($cart->items as $item) {
                $price = $item->dish->base_price;
                if ($item->size && $item->size->price_multiplier) {
                    $price *= $item->size->price_multiplier;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $item->dish_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'size' => $item->size ? $item->size->size : null,
                    'notes' => $item->notes
                ]);

                // تحديث عدد مرات طلب الطبق
                $item->dish->increment('order_count');
            }

            // تفريغ السلة
            $cart->items()->delete();
            $cart->update([
                'coupon_id' => null,
                'discount' => 0,
                'total' => 0
            ]);

            // زيادة عداد استخدامات الكوبون إذا كان مستخدماً
            if ($cart->coupon_id) {
                $cart->coupon->increment('uses');
            }

            // إرسال إشعار للمستخدم
            // TODO: إضافة إشعار

            return response()->json([
                'message' => 'تم إنشاء الطلب بنجاح',
                'order' => $order->load('items.dish', 'address')
            ], 201);
        });
    }

    /**
     * عرض تفاصيل طلب محدد
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('customer_id', $request->user_id)
            ->with(['items.dish', 'address', 'coupon'])
            ->firstOrFail();

        return response()->json([
            'data' => $order,
            'message' => 'تم جلب تفاصيل الطلب بنجاح',
        ]);
    }



    /**
     * إلغاء طلب
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // التحقق من أن الطلب يمكن إلغاؤه
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'message' => 'لا يمكن إلغاء هذا الطلب في حالته الحالية'
            ], 422);
        }

        // تحديث حالة الطلب
        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'data' => $order,
            'message' => 'تم إلغاء الطلب بنجاح',
        ]);
    }

    /**
     * تتبع حالة الطلب (للعملاء)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trackOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['items.dish', 'address'])
            ->firstOrFail();

        // هنا يمكن إضافة منطق إضافي لتتبع الطلب مثل الموقع الحالي للتوصيل

        return response()->json([
            'data' => [
                'order' => $order,
                'status' => $order->status,
                'estimated_delivery_time' => $this->getEstimatedDeliveryTime($order),
            ],
            'message' => 'تم جلب معلومات تتبع الطلب بنجاح',
        ]);
    }

    /**
     * الحصول على الوقت المقدر للتوصيل
     *
     * @param  \App\Models\Order  $order
     * @return string
     */
    private function getEstimatedDeliveryTime($order)
    {
        // حساب الوقت المقدر للتوصيل بناءً على وقت إنشاء الطلب وأوقات تحضير الأطباق
        $preparationTime = $order->items->sum(function ($item) {
            return $item->dish->preparation_time * $item->quantity;
        });

        // إضافة وقت التوصيل (30 دقيقة افتراضياً)
        $deliveryTime = 30;

        $totalMinutes = min($preparationTime + $deliveryTime, 120); // بحد أقصى ساعتين

        $createdAt = $order->created_at;
        $estimatedDelivery = $createdAt->addMinutes($totalMinutes);

        return $estimatedDelivery->format('Y-m-d H:i:s');
    }
}
