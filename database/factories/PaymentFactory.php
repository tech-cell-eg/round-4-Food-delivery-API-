<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = $this->faker->randomElement(['credit_card', 'debit_card', 'cash_on_delivery', 'wallet']);
        $amount = $this->faker->randomFloat(2, 10, 500);
        
        return [
            'order_id' => Order::factory(),
            'payment_method' => $paymentMethod,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'transaction_id' => $this->faker->boolean(80) ? $this->faker->uuid : null,
            'card_token' => in_array($paymentMethod, ['credit_card', 'debit_card']) 
                ? 'card_' . $this->faker->creditCardNumber()
                : null,
            'amount' => $amount,
            'payment_details' => $this->generatePaymentDetails($paymentMethod),
        ];
    }

    /**
     * Generate realistic payment details based on payment method
     */
    private function generatePaymentDetails($paymentMethod): ?string
    {
        switch ($paymentMethod) {
            case 'credit_card':
            case 'debit_card':
                return json_encode([
                    'card_last_four' => $this->faker->numerify('####'),
                    'card_brand' => $this->faker->randomElement(['visa', 'mastercard', 'amex']),
                    'cardholder_name' => $this->faker->name,
                    'expiry_month' => $this->faker->month,
                    'expiry_year' => $this->faker->year,
                ]);
            
            case 'wallet':
                return json_encode([
                    'wallet_type' => $this->faker->randomElement(['vodafone_cash', 'orange_money', 'etisalat_cash']),
                    'wallet_number' => $this->faker->phoneNumber,
                ]);
            
            case 'cash_on_delivery':
                return json_encode([
                    'delivery_address' => $this->faker->address,
                    'expected_delivery_time' => $this->faker->dateTimeBetween('+1 hour', '+2 days'),
                ]);
            
            default:
                return null;
        }
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'transaction_id' => null,
        ]);
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'transaction_id' => 'txn_' . $this->faker->uuid,
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'transaction_id' => 'txn_' . $this->faker->uuid,
            'payment_details' => json_encode([
                'error_code' => $this->faker->randomElement(['insufficient_funds', 'card_declined', 'expired_card', 'network_error']),
                'error_message' => $this->faker->sentence,
            ]),
        ]);
    }

    /**
     * Indicate that the payment is refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'transaction_id' => 'txn_' . $this->faker->uuid,
            'payment_details' => json_encode([
                'refund_reason' => $this->faker->randomElement(['customer_request', 'order_cancelled', 'chef_unavailable']),
                'refund_amount' => $attributes['amount'] ?? $this->faker->randomFloat(2, 10, 500),
                'refund_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ]),
        ]);
    }

    /**
     * Indicate that the payment is via credit card.
     */
    public function creditCard(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'credit_card',
            'card_token' => 'card_' . $this->faker->creditCardNumber(),
        ]);
    }

    /**
     * Indicate that the payment is via debit card.
     */
    public function debitCard(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'debit_card',
            'card_token' => 'card_' . $this->faker->creditCardNumber(),
        ]);
    }

    /**
     * Indicate that the payment is cash on delivery.
     */
    public function cashOnDelivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash_on_delivery',
            'card_token' => null,
        ]);
    }

    /**
     * Indicate that the payment is via wallet.
     */
    public function wallet(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'wallet',
            'card_token' => null,
        ]);
    }

    /**
     * Set a specific order for this payment.
     */
    public function forOrder($orderId): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $orderId,
        ]);
    }
} 

