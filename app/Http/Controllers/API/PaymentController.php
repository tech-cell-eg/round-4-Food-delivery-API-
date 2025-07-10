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
            return response()->json([
                'success' => false,
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

            return response()->json([
                'success' => true,
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
    public function updatePaymentResult(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:completed,failed,cancelled',
            'transaction_id' => 'nullable|string',
            'payment_details' => 'nullable|array'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->status = $request->status;
        $payment->transaction_id = $request->transaction_id ?? $payment->transaction_id;

        if ($request->has('payment_details')) {
            $payment->payment_details = json_encode($request->payment_details);
        }

        $payment->save();

        // تحديث حالة الطلب إذا نجح الدفع
        if ($request->status === 'completed') {
            $order = Order::find($payment->order_id);
            if ($order) {
                $order->status = 'processing';
                $order->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الدفع بنجاح',
            'payment' => [
                'id' => $payment->id,
                'status' => $payment->status,
                'order_id' => $payment->order_id
            ]
        ]);
    }

    /**
     * التحقق من حالة الدفع
     */
    public function checkPaymentStatus($id)
    {
        $order = Order::find($id);
        $payment = Payment::where('order_id', $id)->first();
        return response()->json([
            'success' => true,
            'order' => $order,
            'payment' => $payment
        ]);
    }
}
