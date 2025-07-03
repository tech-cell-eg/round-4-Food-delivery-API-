<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'post_code' => $this->faker->numberBetween(10000, 99999),
            'address_text' => $this->faker->address(),
            'street' => $this->faker->streetName(),
            'appartment' => $this->faker->optional()->numberBetween(1, 300),
            'lable' => $this->faker->optional()->randomElement(['Home', 'Work', 'Other']),
            'is_default' => $this->faker->boolean(30),
        ];
    }
} 