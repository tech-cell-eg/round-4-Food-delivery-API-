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
            'name' => $this->faker->streetName(),
            'display_name' => $this->faker->address(),
            'is_default' => $this->faker->boolean(30),
        ];
    }
}
