<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $customerId = Auth::user()->customer->id;
        $order = Order::where('customer_id', $customerId)
            ->where('id', $request->order_id)
            ->firstOrFail();

        // التحقق من أن الطلب لم يتم دفعه بالفعل
        $existingPayment = Payment::where('order_id', $order->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return response()->json([
                'status' => 'error',
                'message' => 'تم دفع هذا الطلب بالفعل'
            ], 400);
        }

        // في حالة وجود دفع معلق، نقوم بتحديثه
        $payment = Payment::where('order_id', $order->id)->first();

        if (!$payment) {
            $payment = new Payment([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $order->total,
            ]);
        } else {
            $payment->payment_method = $request->payment_method;
        }

        // معالجة الدفع حسب الطريقة
        switch ($request->payment_method) {
            case 'credit_card':
            case 'debit_card':
                // هنا يمكن إضافة التكامل مع بوابة الدفع الفعلية
                $paymentDetails = [
                    'card_number' => substr($request->card_number, -4), // نخزن فقط آخر 4 أرقام للأمان
                    'card_expiry' => $request->card_expiry,
                ];

                // محاكاة عملية الدفع
                $success = true; // يمكن تغييرها لمحاكاة نجاح أو فشل الدفع

                if ($success) {
                    $payment->status = 'completed';
                    $payment->transaction_id = 'TXN_' . uniqid();
                    $payment->payment_details = json_encode($paymentDetails);
                    $order->status = 'processing';
                } else {
                    $payment->status = 'failed';
                    $payment->payment_details = json_encode([
                        'error' => 'فشل في معالجة الدفع'
                    ]);
                }
                break;

            case 'cash_on_delivery':
                $payment->status = 'pending';
                $order->status = 'processing';
                break;

            case 'wallet':
                // هنا يمكن إضافة التحقق من رصيد المحفظة
                $walletBalance = 1000; // يجب استبدالها بالرصيد الفعلي

                if ($walletBalance >= $order->total) {
                    $payment->status = 'completed';
                    $payment->transaction_id = 'WALLET_' . uniqid();
                    $order->status = 'processing';
                } else {
                    $payment->status = 'failed';
                    $payment->payment_details = json_encode([
                        'error' => 'رصيد المحفظة غير كافٍ'
                    ]);
                }
                break;
        }

        $payment->save();
        $order->save();

        if ($payment->status === 'completed') {
            return response()->json([
                'status' => 'success',
                'message' => 'تمت معالجة الدفع بنجاح',
                'data' => [
                    'payment' => $payment,
                    'order' => $order
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في معالجة الدفع',
                'data' => [
                    'payment' => $payment,
                    'order' => $order
                ]
            ], 400);
        }
    }

    /**
     * التحقق من حالة الدفع
     */
    public function checkPaymentStatus($orderId)
    {
        $customerId = Auth::user()->customer->id;
        $order = Order::where('customer_id', $customerId)
            ->where('id', $orderId)
            ->firstOrFail();

        $payment = Payment::where('order_id', $order->id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على معلومات الدفع'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'payment_status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
                'created_at' => $payment->created_at
            ]
        ]);
    }
}
