<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->randomFloat(2, 10, 100);
        $totalPrice = $quantity * $unitPrice;

        return [
            'order_id' => \App\Models\Order::factory(),
            'dish_id' => \App\Models\Dish::factory(),
            'dish_name' => $this->faker->words(2, true),
            'size_name' => $this->faker->randomElement(['small', 'medium', 'large']),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ];
    }

    /**
     * Indicate a small size order item.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'size_name' => 'small',
            'unit_price' => $this->faker->randomFloat(2, 10, 30),
        ]);
    }

    /**
     * Indicate a medium size order item.
     */
    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'size_name' => 'medium',
            'unit_price' => $this->faker->randomFloat(2, 25, 50),
        ]);
    }

    /**
     * Indicate a large size order item.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'size_name' => 'large',
            'unit_price' => $this->faker->randomFloat(2, 40, 80),
        ]);
    }

} 