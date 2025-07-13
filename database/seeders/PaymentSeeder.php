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
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return;
        } else {
            // إنشاء مدفوعات للطلبات الموجودة
            $orders->each(function ($order) {
                // التأكد من أن الطلب لا يملك دفعة بالفعل
                if (!$order->payment) {
                    $this->createPaymentForOrder($order);
                }
            });

            // إنشاء مدفوعات إضافية لطلبات عشوائية
            $randomOrders = $orders->random(min(20, $orders->count()));
            $randomOrders->each(function ($order) {
                // إنشاء دفعة إضافية فقط إذا لم تكن موجودة بالفعل
                if (!$order->payment) {
                    $this->createPaymentForOrder($order);
                }
            });
        }
    }

    /**
     * Create a realistic payment for a specific order
     */
    private function createPaymentForOrder($order)
    {
        // تحديد طريقة الدفع بناءً على نوع الطلب
        $paymentMethods = [
            'credit_card' => 40,      // 40% احتمال
            'debit_card' => 25,       // 25% احتمال
            'cash_on_delivery' => 20, // 20% احتمال
            'wallet' => 15,           // 15% احتمال
        ];

        $paymentMethod = $this->getRandomWeightedElement($paymentMethods);

        // تحديد حالة الدفع
        $statusWeights = [
            'completed' => 70,  // 70% احتمال
            'pending' => 15,    // 15% احتمال
            'failed' => 10,     // 10% احتمال
            'refunded' => 5,    // 5% احتمال
        ];

        $status = $this->getRandomWeightedElement($statusWeights);

        // إنشاء الدفعة
        $payment = Payment::factory()
            ->forOrder($order->id)
            ->create([
                'payment_method' => $paymentMethod,
                'status' => $status,
                'amount' => $order->total ?? fake()->randomFloat(2, 50, 300),
            ]);

        // تطبيق states محددة بناءً على الحالة
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
                    ]),
                ]);
                break;
            
            case 'refunded':
                $payment->update([
                    'transaction_id' => 'txn_' . fake()->uuid,
                    'payment_details' => json_encode([
                        'refund_reason' => fake()->randomElement(['customer_request', 'order_cancelled', 'chef_unavailable']),
                        'refund_amount' => $payment->amount,
                        'refund_date' => fake()->dateTimeBetween('-1 month', 'now'),
                    ]),
                ]);
                break;
        }

        return $payment;
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