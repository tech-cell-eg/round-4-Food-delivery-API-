<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * معالجة دفع لطلب معين
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:credit_card,cash_on_delivery,wallet',
            'payment_details' => 'required_if:payment_method,credit_card|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التحقق من أن الطلب ينتمي للمستخدم الحالي
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // التحقق من أن الطلب لم يتم دفعه بالفعل
        if ($order->payment()->exists()) {
            return response()->json([
                'message' => 'تم دفع هذا الطلب بالفعل'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // معالجة الدفع حسب الطريقة المختارة
            $paymentStatus = 'pending';
            $transactionId = null;

            switch ($request->payment_method) {
                case 'credit_card':
                    // هنا يمكن إضافة منطق معالجة الدفع ببطاقة الائتمان
                    // مثل الاتصال ببوابة دفع خارجية
                    $paymentStatus = $this->processCreditCardPayment($request->payment_details, $order);
                    $transactionId = 'CC-' . time() . '-' . rand(1000, 9999);
                    break;
                
                case 'cash_on_delivery':
                    // لا يلزم معالجة خاصة للدفع عند الاستلام
                    $paymentStatus = 'pending';
                    $transactionId = 'COD-' . time() . '-' . rand(1000, 9999);
                    break;
                
                case 'wallet':
                    // هنا يمكن إضافة منطق معالجة الدفع من المحفظة الإلكترونية
                    $paymentStatus = $this->processWalletPayment($request->user(), $order);
                    $transactionId = 'WAL-' . time() . '-' . rand(1000, 9999);
                    break;
            }

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'payment_method' => $request->payment_method,
                'transaction_id' => $transactionId,
                'status' => $paymentStatus,
                'payment_details' => $request->payment_method === 'credit_card' ? json_encode($this->sanitizePaymentDetails($request->payment_details)) : null,
            ]);

            // تحديث حالة الطلب
            if ($paymentStatus === 'completed') {
                $order->status = 'processing';
            } elseif ($request->payment_method === 'cash_on_delivery') {
                $order->status = 'processing';
            }
            $order->save();

            DB::commit();

            return response()->json([
                'data' => $payment->load('order'),
                'message' => 'تم معالجة الدفع بنجاح',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل دفع معين
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $payment = Payment::where('id', $id)
            ->whereHas('order', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->with('order')
            ->firstOrFail();

        return response()->json([
            'data' => $payment,
            'message' => 'تم جلب تفاصيل الدفع بنجاح',
        ]);
    }

    /**
     * معالجة الدفع ببطاقة الائتمان
     * هذه دالة وهمية تحاكي عملية الدفع ببطاقة الائتمان
     *
     * @param  array  $paymentDetails
     * @param  \App\Models\Order  $order
     * @return string
     */
    private function processCreditCardPayment($paymentDetails, $order)
    {
        // التحقق من وجود تفاصيل بطاقة الائتمان المطلوبة
        if (
            !isset($paymentDetails['card_number']) ||
            !isset($paymentDetails['expiry_month']) ||
            !isset($paymentDetails['expiry_year']) ||
            !isset($paymentDetails['cvv'])
        ) {
            throw new \Exception('تفاصيل بطاقة الائتمان غير مكتملة');
        }

        // هنا يمكن إضافة منطق الاتصال ببوابة الدفع الحقيقية
        // هذا مجرد محاكاة للعملية

        // التحقق من صحة رقم البطاقة (تبسيط)
        $cardNumber = preg_replace('/\D/', '', $paymentDetails['card_number']);
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            throw new \Exception('رقم البطاقة غير صالح');
        }

        // التحقق من تاريخ انتهاء الصلاحية
        $expiryMonth = (int) $paymentDetails['expiry_month'];
        $expiryYear = (int) $paymentDetails['expiry_year'];
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        if (
            $expiryYear < $currentYear ||
            ($expiryYear === $currentYear && $expiryMonth < $currentMonth) ||
            $expiryMonth < 1 ||
            $expiryMonth > 12
        ) {
            throw new \Exception('تاريخ انتهاء الصلاحية غير صالح');
        }

        // محاكاة نجاح العملية (90% من الحالات)
        if (rand(1, 100) <= 90) {
            return 'completed';
        } else {
            throw new \Exception('فشلت عملية الدفع. يرجى المحاولة مرة أخرى');
        }
    }

    /**
     * معالجة الدفع من المحفظة الإلكترونية
     * هذه دالة وهمية تحاكي عملية الدفع من المحفظة
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return string
     */
    private function processWalletPayment($user, $order)
    {
        // هنا يمكن إضافة منطق التحقق من رصيد المحفظة وخصم المبلغ
        // هذا مجرد محاكاة للعملية

        // محاكاة نجاح العملية (95% من الحالات)
        if (rand(1, 100) <= 95) {
            return 'completed';
        } else {
            throw new \Exception('رصيد المحفظة غير كافٍ');
        }
    }

    /**
     * تنظيف بيانات الدفع الحساسة قبل تخزينها
     *
     * @param  array  $paymentDetails
     * @return array
     */
    private function sanitizePaymentDetails($paymentDetails)
    {
        // إخفاء معظم أرقام البطاقة
        if (isset($paymentDetails['card_number'])) {
            $cardNumber = preg_replace('/\D/', '', $paymentDetails['card_number']);
            $paymentDetails['card_number'] = str_repeat('*', strlen($cardNumber) - 4) . substr($cardNumber, -4);
        }

        // إزالة رمز CVV تمامًا
        if (isset($paymentDetails['cvv'])) {
            unset($paymentDetails['cvv']);
        }

        return $paymentDetails;
    }
}
