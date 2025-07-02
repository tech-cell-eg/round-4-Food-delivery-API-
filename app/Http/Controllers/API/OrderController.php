<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
     * عرض تفاصيل طلب محدد
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['items.dish', 'address', 'payment'])
            ->firstOrFail();

        return response()->json([
            'data' => $order,
            'message' => 'تم جلب تفاصيل الطلب بنجاح',
        ]);
    }

    /**
     * إنشاء طلب جديد من سلة التسوق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string',
        ]);

        // التحقق من أن العنوان ينتمي للمستخدم الحالي
        $address = Address::where(['customer_id' => $validated['customer_id'], 'is_default' => true])
            ->first();

        if (!$address) {
            return response()->json([
                'message' => 'الرجاء اختيار عنوان صالح'
            ], 422);
        }

        // الحصول على سلة التسوق النشطة للمستخدم
        $cart = Cart::where('customer_id', $validated['customer_id'])
            ->where('status', 'active')
            ->with(['items.dish', 'items.size', 'coupon'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'سلة التسوق فارغة'
            ], 422);
        }

        try {
            DB::beginTransaction();

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

            // إنشاء الطلب
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'address_id' => $address->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'notes' => $request->notes,
                'coupon_id' => $cart->coupon_id,
            ]);

            // إضافة عناصر الطلب
            foreach ($cart->items as $item) {
                $price = $item->dish->base_price;
                if ($item->size) {
                    $price *= $item->size->price_multiplier;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $item->dish_id,
                    'size_id' => $item->size_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'subtotal' => $price * $item->quantity,
                ]);
            }

            // تحديث حالة سلة التسوق
            $cart->update(['status' => 'completed']);

            DB::commit();

            return response()->json([
                'data' => $order->load(['items.dish', 'address']),
                'message' => 'تم إنشاء الطلب بنجاح',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage()
            ], 500);
        }
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
