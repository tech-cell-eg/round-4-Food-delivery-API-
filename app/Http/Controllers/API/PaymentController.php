<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * معالجة عملية الدفع للطلب
     * 
     * يستقبل معلومات الدفع من الفرونت إند ويقوم بإنشاء سجل دفع
     * ويعيد معرف الدفع للفرونت إند لمتابعة عملية الدفع
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:credit_card,debit_card,cash_on_delivery,wallet',
            'amount' => 'required|numeric|min:0',
            'card_token' => 'required_if:payment_method,credit_card,debit_card'
        ]);

        $customerId = Auth::user()->customer->id;

        // التحقق من وجود الطلب وأنه ينتمي للعميل الحالي
        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order) {
            return ApiResponse::error([
                'message' => 'الطلب غير موجود أو لا ينتمي لك'
            ], 404);
        }

        // إنشاء سجل دفع جديد بحالة معلقة
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => 'pending',
            'card_token' => $request->card_token ?? null,
        ]);

        Log::info('تم إنشاء سجل دفع جديد', ['payment_id' => $payment->id]);

        // إذا كانت طريقة الدفع هي الدفع عند الاستلام
        if ($request->payment_method === 'cash_on_delivery') {
            // تحديث حالة الطلب إلى معلق
            $order->update(['status' => 'pending']);

            return ApiResponse::success([
                'message' => 'تم تسجيل طلبك بنجاح وسيتم الدفع عند الاستلام',
                'payment_id' => $payment->id,
                'order_id' => $order->id
            ]);
        }

        // بالنسبة لطرق الدفع الأخرى، نعيد معرف الدفع للفرونت إند
        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء طلب الدفع بنجاح',
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method
        ]);
    }

    /**
     * تحديث نتيجة الدفع بعد انتهاء العملية من الفرونت
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:succeeded,failed,cancelled',
            'transaction_id' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string'
        ]);

        $order = Order::find($id);
        $payment = $order->payment;
        $payment->update([
            'status'            => $request->status,
            'transaction_id'    => $request->transaction_id,
            'amount'            => $request->amount,
            'payment_method'    => $request->payment_method
        ]);

        // تحديث حالة الطلب إذا نجح الدفع
        if ($request->status === 'succeeded') {
            $order = Order::find($payment->order_id);

            $order->update(['payment_status' => 'paid', 'paid_at' => now()]);
            $order->logOrderStatus('paid', ' تم الدفع للطلب  رقم ' . $order->order_number);
        }

        return ApiResponse::success([
            'data' => [
                'id' => $payment->id,
                'status' => $payment->status,
                'order' => $payment->order
            ]
        ], 'تم تحديث حالة الدفع للطلب بنجاح', 200);
    }

    /**
     * التحقق من حالة الدفع
     */
    public function checkPaymentStatus($id)
    {
        $order = Order::find($id);
        $payment = Payment::where('order_id', $id)->first();
        return ApiResponse::success([
            'order' => $order,
            'payment' => $payment
        ], 'تم جلب حالة الدفع بنجاح', 200);
    }
}
