<?php

namespace Database\Factories;

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
            'customer_id' => \App\Models\Customer::factory(),
            'address_id' => \App\Models\Address::factory(),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'coupon_id' => $this->faker->optional(0.3)->randomElement(\App\Models\Coupon::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(['pending', 'processing', 'on_the_way', 'delivered', 'cancelled']),
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
     * Indicate that the order is on the way.
     */
    public function onTheWay(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'on_the_way',
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