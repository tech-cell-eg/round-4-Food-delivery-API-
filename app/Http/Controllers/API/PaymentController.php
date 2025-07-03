<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * معالجة عملية الدفع للطلب
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:credit_card,debit_card,cash_on_delivery,wallet',
            'card_number' => 'required_if:payment_method,credit_card,debit_card',
            'card_expiry' => 'required_if:payment_method,credit_card,debit_card',
            'card_cvv' => 'required_if:payment_method,credit_card,debit_card',
        ]);

        $customerId = 1; // سيتم استبداله بـ Auth::user()->customer->id
        $order = Order::find($request->order_id);

        // التحقق من أن الطلب لم يتم دفعه بالفعل
        $existingPayment = Payment::where('order_id', $order->id)
            ->whereIn('status', ['completed', 'pending'])
            ->first();

        if ($existingPayment) {
            return response()->json([
                'status' => 'error',
                'message' => 'يوجد عملية دفع سابقة لهذا الطلب'
            ], 400);
        }

        // إنشاء سجل دفع جديد
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount' => $order->total,
            'status' => 'pending',
        ]);
        // تسجيل معلومات التكوين للمساعدة في التشخيص
        \Log::info('Stripe Config:', [
            'key' => config('services.stripe.key'),
            'secret_exists' => !empty(config('services.stripe.secret')),
            'secret_length' => config('services.stripe.secret') ? strlen(config('services.stripe.secret')) : 0
        ]);

        try {
            $stripe = new \Stripe\StripeClient([
                'api_key' => config('services.stripe.secret'),
                'stripe_version' => '2023-10-16' // تحديد إصدار API
            ]);
            \Log::info('Stripe client initialized successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to initialize Stripe client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'فشل في تهيئة خدمة الدفع: ' . $e->getMessage()
            ], 500);
        }
        
        DB::beginTransaction();
        try {
            // معالجة الدفع حسب الطريقة
            switch ($request->payment_method) {
                case 'credit_card':
                case 'debit_card':
                    // التكامل مع بوابة الدفع (مثال: Stripe)
                    try {
                        // 1. التحقق من صحة البطاقة
                        $this->validateCard($request);

                        // 2. معالجة الدفع باستخدام بطاقة اختبارية
                        Log::info('Creating payment intent for order', [
                            'order_id' => $order->id,
                            'amount' => $order->total * 100,
                            'currency' => 'egp'
                        ]);

                        $expiry = explode('/', $request->card_expiry);
                        try {
                            // إنشاء طريقة دفع جديدة
                            $paymentMethod = $stripe->paymentMethods->create([
                                'type' => 'card',
                                'card' => [
                                    'number' => $request->card_number,
                                    'exp_month' => $expiry[0],
                                    'exp_year' => $expiry[1],
                                    'cvc' => $request->card_cvv,
                                ],
                            ]);

                            // إنشاء نية دفع جديدة
                            $paymentIntent = $stripe->paymentIntents->create([
                                'amount' => $order->total * 100, // تحويل المبلغ إلى أصغر وحدة (قرش)
                                'currency' => 'egp',
                                'payment_method_types' => ['card'],
                                'payment_method' => $paymentMethod->id,
                                'confirm' => true,
                                'confirmation_method' => 'manual',
                                'return_url' => route('payment.return'),
                                'metadata' => [
                                    'order_id' => $order->id,
                                    'customer_id' => $customerId
                                ]
                            ]);

                            // التحقق من حالة الدفع
                            if ($paymentIntent->status === 'succeeded') {
                                $payment->status = 'completed';
                                $payment->transaction_id = $paymentIntent->id;
                                $order->status = 'processing';
                            } else if ($paymentIntent->status === 'requires_action') {
                                // إذا تطلب الأمر إجراءً إضافياً (مثل 3D Secure)
                                return response()->json([
                                    'success' => true,
                                    'requires_action' => true,
                                    'client_secret' => $paymentIntent->client_secret,
                                    'payment_intent_id' => $paymentIntent->id
                                ]);
                            } else {
                                throw new \Exception('فشل في معالجة الدفع: ' . $paymentIntent->last_payment_error->message);
                            }
                            $message = 'تمت معالجة الدفع بنجاح';
                        } catch (\Exception $e) {
                            $payment->status = 'failed';
                            $payment->payment_details = json_encode([
                                'error' => $e->getMessage(),
                                'code' => $e->getCode()
                            ]);
                            throw $e;
                        }
                    } catch (\Exception $e) {
                        throw new \Exception('فشل في معالجة الدفع');
                        $payment->payment_details = json_encode([
                            'error' => $e->getMessage(),
                            'code' => $e->getCode()
                        ]);
                        throw $e;
                    }
                    break;

                case 'cash_on_delivery':
                    $payment->status = 'pending';
                    $order->status = 'pending_payment';
                    $message = 'سيتم الدفع عند الاستلام';
                    break;

                case 'wallet':
                    // التحقق من رصيد المحفظة
                    $walletBalance = $this->getCustomerWalletBalance($customerId);

                    if ($walletBalance >= $order->total) {
                        // خصم المبلغ من المحفظة
                        $this->deductFromWallet($customerId, $order->total);

                        $payment->status = 'completed';
                        $payment->transaction_id = 'WALLET_' . uniqid();
                        $order->status = 'processing';
                        $message = 'تمت معالجة الدفع من المحفظة بنجاح';
                    } else {
                        throw new \Exception('رصيد المحفظة غير كافٍ');
                    }
                    break;
            }

            $payment->save();
            $order->save();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $message ?? 'تمت معالجة الدفع بنجاح',
                'data' => [
                    'order' => $order->load('orderItems'),
                    'payment' => $payment
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في معالجة الدفع: ' . $e->getMessage(),
                'data' => [
                    'order' => $order,
                    'payment' => $payment ?? null
                ]
            ], 400);
        }
    }

    /**
     * التحقق من صحة بيانات البطاقة
     */
    private function validateCard($request)
    {
        // التحقق من تاريخ انتهاء الصلاحية
        $expiry = explode('/', $request->card_expiry);
        if (count($expiry) !== 2 || !is_numeric($expiry[0]) || !is_numeric($expiry[1])) {
            throw new \Exception('صيغة تاريخ انتهاء البطاقة غير صحيحة');
        }

        $month = (int)$expiry[0];
        $year = (int)$expiry[1] + 2000; // تحويل السنة إلى صيغة كاملة
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            throw new \Exception('انتهت صلاحية البطاقة');
        }

        if ($month < 1 || $month > 12) {
            throw new \Exception('شهر انتهاء الصلاحية غير صالح');
        }

        // التحقق من رقم البطاقة (Luhn Algorithm)
        $cardNumber = preg_replace('/\D/', '', $request->card_number);
        if (!preg_match('/^[0-9]{13,19}$/', $cardNumber)) {
            throw new \Exception('رقم البطاقة غير صالح');
        }

        $sum = 0;
        $length = strlen($cardNumber);
        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$cardNumber[$length - $i - 1];
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        if ($sum % 10 !== 0) {
            throw new \Exception('رقم البطاقة غير صالح');
        }
    }

    /**
     * الحصول على رصيد المحفظة
     */
    private function getCustomerWalletBalance($customerId)
    {
        // هنا يتم جلب رصيد المحفظة من قاعدة البيانات
        // هذا مثال بسيط، يجب استبداله بالكود الفعلي
        return 1000;
    }

    /**
     * خصم مبلغ من المحفظة
     */
    private function deductFromWallet($customerId, $amount)
    {
        // هنا يتم خصم المبلغ من المحفظة في قاعدة البيانات
        // هذا مثال بسيط، يجب استبداله بالكود الفعلي
        return true;
    }

    /**
     * التحقق من حالة الدفع
     */
    public function checkPaymentStatus($orderId)
    {
        $customerId = 1; // سيتم استبداله بـ Auth::user()->customer->id
        $order = Order::where('customer_id', $customerId)
            ->where('id', $orderId)
            ->firstOrFail();

        $payment = Payment::where('order_id', $order->id)
            ->latest()
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على معلومات الدفع'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'order_id' => $order->id,
                'order_status' => $order->status,
                'payment_status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
                'paid_at' => $payment->paid_at,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]
        ]);
    }

    /**
     * عرض صفحة إضافة البطاقة (view)
     */
    public function addPaymentMethod()
    {
        return view('payment_method');
    }

    /**
     * حفظ طريقة دفع جديدة باستخدام payment_method_id من Stripe.js
     */
    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            // جلب بيانات البطاقة من Stripe للتأكيد
            $paymentMethod = $stripe->paymentMethods->retrieve($request->payment_method_id, []);
            // يمكنك هنا ربط البطاقة بعميل أو تخزين الـ id فقط
            return response()->json([
                'status' => 'success',
                'payment_method' => $paymentMethod
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * جلب بيانات طريقة دفع من Stripe عبر id
     */
    public function getPaymentMethod($id)
    {
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $paymentMethod = $stripe->paymentMethods->retrieve($id, []);
            return response()->json([
                'status' => 'success',
                'payment_method' => $paymentMethod
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * تحديث نتيجة الدفع بعد انتهاء العملية من الفرونت
     */
    public function updateResult(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:completed,failed,cancelled',
            'transaction_id' => 'nullable|string',
            // يمكن إضافة المزيد حسب الحاجة
        ]);

        $payment = Payment::findOrFail($id);
        $payment->status = $request->status;
        $payment->transaction_id = $request->transaction_id ?? $payment->transaction_id;
        $payment->payment_details = json_encode($request->all());
        $payment->save();

        // تحديث حالة الطلب إذا نجح الدفع
        if ($request->status === 'completed') {
            $order = $payment->order;
            if ($order) {
                $order->status = 'processing';
                $order->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}
