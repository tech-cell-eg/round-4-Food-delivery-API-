<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => '+2010' . fake()->numerify('#######'),
            'profile_image' => fake()->imageUrl(300, 300, 'people'),
            'bio' => fake()->paragraph(),
            'password' => Hash::make('password'),

        ];
    }
    public function chef()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'chef',
                'bio' => $this->faker->paragraph() . ' Professional chef specializing in ' .
                    $this->faker->randomElement(['Italian', 'Mediterranean', 'Middle Eastern', 'Asian', 'French']) . ' cuisine.',
            ];
        });
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'customer',
                'bio' => $this->faker->randomElement([
                    'Food enthusiast exploring new cuisines',
                    'Home cook looking to improve skills',
                    'Busy professional who appreciates quality meals',
                    'Health-conscious eater',
                    'Parent cooking for family',
                    'Student learning to cook',
                ]),
            ];
        });
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
