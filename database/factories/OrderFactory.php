<?php

namespace Database\Factories;

use App\Models\Chef;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 500);
        $deliveryFee = $this->faker->randomFloat(2, 10, 50);
        $tax = $subtotal * 0.15; // 15% tax
        $discount = $this->faker->randomFloat(2, 0, $subtotal * 0.2); // up to 20% discount
        $total = $subtotal + $deliveryFee + $tax - $discount;

        return [
            'chef_id' => Chef::factory(),
            'customer_id' => Customer::factory(),
            'address_id' => Address::factory(),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'coupon_id' => $this->faker->optional(0.3)->randomElement(Coupon::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    /**
     * Indicate that the order is out for delivery.
     */
    public function outForDelivery(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_for_delivery',
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
} 