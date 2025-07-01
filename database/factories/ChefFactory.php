<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chef>
 */
class ChefFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => User::factory()->create([
                'type' => 'chef',
            ])->id,
            'national_id' => $this->faker->numerify('##############'), // 14 digits
            'balance' => $this->faker->randomFloat(2, 0, 5000),
            'description' => $this->faker->paragraph,
            'stripe_account_id' => $this->faker->uuid,
            'location' => $this->faker->address,
            'is_verified' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
}
