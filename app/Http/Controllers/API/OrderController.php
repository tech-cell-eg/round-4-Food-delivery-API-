<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOrderResource;
use App\Models\Address;
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
use Illuminate\Support\Facades\Validator;

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

        return ApiResponse::withPagination($orders, 'تم جلب طلبات المستخدم بنجاح', 200);
    }


    /**
     * عرض طلبات المستخدم حسب الطلب
     */
    public function getCustomerOrdersByStatus(Request $request)
    {
        $customer = User::find(Auth::id());
        if ($customer->type !== 'customer') {
            return ApiResponse::error([
                'message' => 'ليس لديك صلاحية الاطلاع على هذه البيانات'
            ], 403);
        }
        // دعم استقبال status كمصفوفة أو نص مفصول بفواصل
        $statusParam = $request->query('status');
        $statusArray = [];
        if (is_array($statusParam)) {
            $statusArray = $statusParam;
        } elseif (is_string($statusParam)) {
            $statusArray = array_map('trim', explode(',', $statusParam));
        }
        // إرجاع كل الطلبات إذا لم يتم تمرير status
        if (!$statusParam || in_array('all', $statusArray)) {
            $orders = Order::where('customer_id', $customer->id)
                ->with(['orderItems.dish', 'payments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('customer_id', $customer->id)
                ->with(['orderItems.dish', 'payments'])
                ->whereIn('status', $statusArray)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return ApiResponse::withPagination(
            CustomerOrderResource::collection($orders),
            'تم جلب طلبات المستخدم بنجاح',
            200
        );
    }

    /**
     * عرض طلبات المستخدم حسب الطلب
     */
    public function getChefOrdersByStatus(Request $request)
    {
        $chef = User::find(Auth::id());
        if ($chef->type !== 'chef') {
            return ApiResponse::error([
                'message' => 'ليس لديك صلاحية الاطلاع على هذه البيانات'
            ], 403);
        }
        $statusArray = $request->query('status') ? explode(',', $request->query('status')) : [];
        //return all orders if status is not provided
        $orders = [];
        if (!$request->query('status')) {
            $orders = Order::where('chef_id', $chef->id)
                ->with(['orderItems', 'payments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif (in_array('all', $statusArray)) {
            $orders = Order::where('chef_id', $chef->id)
                ->with(['orderItems', 'payments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('chef_id', $chef->id)
                ->with(['orderItems', 'payments'])
                ->whereIn('status', $statusArray)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return ApiResponse::withPagination($orders, 'تم جلب طلبات المستخدم بنجاح', 200);
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function show($id)
    {
        $customerId = Auth::id();

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
            'order' => $order->load('payments'),
        ], 'تم جلب بيانات الطلب بنجاح', 200);
    }

    /**
     * إنشاء طلب جديد من سلة التسوق
     */
    public function store(Request $request)
    {
        $customerId = Auth::id();

        $cart = Cart::with(['items.dish.chef'])->where('customer_id', $customerId)->first();

        $defultAddress = Address::where("customer_id", $customerId)->where("is_default", 1)->first();
        if(! $defultAddress) {
            $defultAddress = Address::where("customer_id", $customerId)->first();
        }
        if(! $defultAddress) {
            return ApiResponse::error("المستخدم ليس لديه عنوان");
        }

        if (!$cart || $cart->items->isEmpty()) {
            return ApiResponse::error([
                'message' => 'سلة التسوق فارغة'
            ], 400);
        }

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
                'payment_method' => "stripe",
                'chef_id' => $cart->items->first()->dish->chef_id,
                'customer_id' => $customerId,
                'address_id' => $defultAddress->id,
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
            $order->logOrderStatus('created', 'تم إنشاء طلب بالرقم' . $order->order_number);

            // إضافة عناصر الطلب
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'chef_id' => $order->chef_id,
                    'dish_id' => $item->dish_id,
                    'size' => $item->size_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'payment_method' => "credit_card",
                'amount' => $total,
            ]);

            // تفريغ سلة التسوق
            $cart->dropItems();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الطلب بنجاح',
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
     * عرض قائمة طلبات العمسيل صاحب الجلسة حسب المطلوب
     */
    public function getCustomerOrders(Request $request)
    {
        if (Auth::user()->type !== 'customer') {
            return ApiResponse::error([
                'message' => 'غير مصرح لك بالوصول إلى هذه البيانات'
            ], 403);
        }
        // يتم ارجاع الطلبات حسب الحالة المرسلة أو كل الطلبات اذا كانت لا توجد حالة
        $query = $request->query();
        if (in_array($query['status'], ['pending', 'completed', 'cancelled'])) {
            $orders = Order::where('customer_id', Auth::id())
                ->where('status', $query['status'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('customer_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return ApiResponse::success([
            'orders' => $orders
        ], 'تم جلب طلبات المستخدم بنجاح', 200);
    }

    /**
     * عرض قائمة طلبات الواردة الى المطعم صاحب الجلسة حسب المطلوب
     */
    public function getChefOrders(Request $request)
    {
        if (Auth::user()->type !== 'chef') {
            return ApiResponse::error([
                'message' => 'غير مصرح لك بالوصول إلى هذه البيانات'
            ], 403);
        }
        // يتم ارجاع الطلبات حسب الحالة المرسلة أو كل الطلبات اذا كانت لا توجد حالة
        $query = $request->query();
        if (in_array($query['status'], ['pending', 'completed', 'cancelled'])) {
            $orders = Order::where('chef_id', Auth::id())
                ->where('status', $query['status'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('chef_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return ApiResponse::success([
            'orders' => $orders
        ], 'تم جلب طلبات المستخدم بنجاح', 200);
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

    // public function trackOrder($id)
    // {
    //     $customerId = Auth::user()->customer->id;
    //     $order = Order::where('customer_id', $customerId)
    //         ->where('id', $id)
    //         ->first();

    //     if (!$order) {
    //         return ApiResponse::error([
    //             'message' => 'لم يتم العثور على الطلب'
    //         ], 400);
    //     }

    //     return ApiResponse::success([
    //         'order' => $order->load(['orderItems', 'statusHistories'])
    //     ], 'تم جلب بيانات الطلب بنجاح', 200);
    // }

    /* change order status */


    public function changeOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return ApiResponse::error([
                'message' => 'لم يتم العثور على الطلب'
            ], 400);
        }

        $order->status = $validated['status'];
        $order->save();
        $this->logOrderStatus($order, $validated['status'], 'تم تغيير حالة الطلب');

        return ApiResponse::success([
            'order' => $order->load('orderItems')
        ], 'تم تغيير حالة الطلب بنجاح', 200);
    }

    public function chefOrders()
    {
        $chefId = Auth::id();
        if (Auth::user()->type !== 'chef') {
            return ApiResponse::error([
                'chef_id' => $chefId,
                'user_type' => Auth::user()->type,
                'message' => 'ليس لديك صلاحية الاطلاع على هذه البيانات',
                'data' => null
            ], 403);
        }
        $orders = Order::with(['orderItems', 'payments'])
            ->where('chef_id', $chefId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ApiResponse::success([
            'orders' => $orders
        ], 'تم جلب طلبات المطابخ بنجاح', 200);
    }

    public function chefOngoingOrders(Request $request)
    {
        $chef = Auth::user();
        if ($chef->type !== 'chef') {
            return ApiResponse::unauthorized('You are not authorized to access this resource.');
        }

        $orders = Order::where(['chef_id' => $chef->id, 'status' => 'pending', 'payment_status' => 'paid'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ApiResponse::success([
            'orders' => $orders
        ], 'تم جلب طلبات المطابخ بنجاح', 200);
    }

    /* Chef Completed Orders */
    public function chefCompletedOrders(Request $request)
    {
        $chef = Auth::user();
        if ($chef->type !== 'chef') {
            return ApiResponse::unauthorized('You are not authorized to access this resource.');
        }

        $orders = Order::where(['status' => 'completed', 'chef_id' => $chef->id])
            ->with(['orderItems.dish'])
            ->paginate(5);
        return ApiResponse::withPagination($orders);
    }
}
