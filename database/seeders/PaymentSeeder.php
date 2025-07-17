<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;


class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود orders في قاعدة البيانات
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return;
        }


        // إنشاء مدفوعات للطلبات التي لا تملك مدفوعات
        $ordersWithoutPayments = $orders->filter(function ($order) {
            return !$order->payment()->exists();
        });

        if ($ordersWithoutPayments->isEmpty()) {
            return;
        }


        $successCount = 0;
        $failCount = 0;

        // إنشاء مدفوعات للطلبات بدون مدفوعات
        $ordersWithoutPayments->each(function ($order) use (&$successCount, &$failCount) {
            try {
                $this->createPaymentForOrder($order);
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
            }
        });


    }

    /**
     * Create a realistic payment for a specific order
     */
    private function createPaymentForOrder($order)
    {
        // التحقق من صحة بيانات الطلب
        if (!$order->total || $order->total <= 0) {
            throw new \Exception("الطلب {$order->id} لا يحتوي على إجمالي صحيح");
        }

        // تحديد طريقة الدفع بناءً على نوع الطلب
        $paymentMethods = [
            'credit_card' => 40,      // 40% احتمال
            'debit_card' => 25,       // 25% احتمال
            'cash_on_delivery' => 20, // 20% احتمال
            'wallet' => 15,           // 15% احتمال
        ];

        $paymentMethod = $this->getRandomWeightedElement($paymentMethods);

        // تحديد حالة الدفع بناءً على حالة الطلب
        $statusWeights = $this->getStatusWeightsBasedOnOrder($order);
        $status = $this->getRandomWeightedElement($statusWeights);

        // إنشاء الدفعة مع البيانات الصحيحة
        $payment = Payment::factory()
            ->forOrder($order->id)
            ->create([
                'payment_method' => $paymentMethod,
                'status' => $status,
                'amount' => $order->total,
            ]);

        // تطبيق states محددة بناءً على الحالة
        $this->applyPaymentStateDetails($payment, $status);

        return $payment;
    }

    /**
     * Get status weights based on order status
     */
    private function getStatusWeightsBasedOnOrder($order): array
    {
        // إذا كان الطلب مُسلم، فالمدفوعة يجب أن تكون مكتملة أيضاً
        if ($order->status === 'delivered') {
            return [
                'completed' => 95,  // 95% احتمال
                'refunded' => 5,    // 5% احتمال للمرتجعات
            ];
        }

        // إذا كان الطلب ملغي، فالمدفوعة يجب أن تكون فاشلة أو مرتجعة
        if ($order->status === 'cancelled') {
            return [
                'failed' => 60,     // 60% احتمال
                'refunded' => 40,   // 40% احتمال
            ];
        }

        // إذا كان الطلب في طور التوصيل، فالمدفوعة غالباً مكتملة
        if ($order->status === 'out_for_delivery') {
            return [
                'completed' => 90,  // 90% احتمال
                'pending' => 10,    // 10% احتمال
            ];
        }

        // إذا كان الطلب قيد المعالجة، فالمدفوعة مكتملة أو قيد الانتظار
        if ($order->status === 'processing') {
            return [
                'completed' => 85,  // 85% احتمال
                'pending' => 15,    // 15% احتمال
            ];
        }

        // للطلبات المعلقة (pending)
        if ($order->status === 'pending') {
            return [
                'pending' => 50,    // 50% احتمال
                'completed' => 30,  // 30% احتمال
                'failed' => 20,     // 20% احتمال
            ];
        }

        // الحالة الافتراضية لأي حالة أخرى
        return [
            'completed' => 60,  // 60% احتمال
            'pending' => 25,    // 25% احتمال
            'failed' => 10,     // 10% احتمال
            'refunded' => 5,    // 5% احتمال
        ];
    }

    /**
     * Apply specific details based on payment status
     */
    private function applyPaymentStateDetails($payment, $status)
    {
        switch ($status) {
            case 'completed':
                $payment->update([
                    'transaction_id' => 'txn_' . fake()->uuid,
                ]);
                break;

            case 'pending':
                $payment->update([
                    'transaction_id' => null,
                ]);
                break;

            case 'failed':
                $payment->update([
                    'transaction_id' => 'txn_' . fake()->uuid,
                    'payment_details' => json_encode([
                        'error_code' => fake()->randomElement(['insufficient_funds', 'card_declined', 'expired_card', 'network_error']),
                        'error_message' => fake()->sentence,
                        'failure_time' => now()->subMinutes(rand(5, 120)),
                    ]),
                ]);
                break;

            case 'refunded':
                $payment->update([
                    'transaction_id' => 'txn_' . fake()->uuid,
                    'payment_details' => json_encode([
                        'refund_reason' => fake()->randomElement(['customer_request', 'order_cancelled', 'chef_unavailable', 'quality_issue']),
                        'refund_amount' => $payment->amount,
                        'refund_date' => fake()->dateTimeBetween('-1 month', 'now'),
                        'refund_transaction_id' => 'ref_' . fake()->uuid,
                    ]),
                ]);
                break;
        }
    }

    /**
     * Get a random element based on weights
     */
    private function getRandomWeightedElement($weightedValues)
    {
        $rand = mt_rand(1, array_sum($weightedValues));

        foreach ($weightedValues as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $key;
            }
        }

        return array_key_first($weightedValues);
    }
}
