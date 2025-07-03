<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => \App\Models\User::factory(),
            'preferred_payment_method' => fake()->randomElement([
                'credit_card',
                'paypal',
                'bank_transfer',
                'crypto',
                'cash'
            ]),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    
    public function forUser(User $user)
    {
        return $this->state([
            'id' => $user->id,
        ]);
    }
}
