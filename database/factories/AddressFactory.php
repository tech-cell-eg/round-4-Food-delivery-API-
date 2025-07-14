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
            'lat' => $this->faker->latitude(20, 35), // Saudi Arabia latitude range
            'lon' => $this->faker->longitude(34, 56), // Saudi Arabia longitude range
            'class' => $this->faker->randomElement(['residential', 'commercial', 'industrial']),
            'type' => $this->faker->randomElement(['house', 'apartment', 'office', 'building']),
            'place_rank' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->streetName(),
            'importance' => $this->faker->randomFloat(2, 0, 1),
            'display_name' => $this->faker->address(),
            'address' => $this->faker->streetAddress(),
            'is_default' => $this->faker->boolean(30),
        ];
    }
} 