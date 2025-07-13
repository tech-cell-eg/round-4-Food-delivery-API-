<?php

namespace Database\Factories;

use App\Models\Chef;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chef_id' => Chef::factory(),
            'customer_id' => Customer::factory(),
            'last_message_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the conversation has no messages yet.
     */
    public function withoutMessages(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_message_at' => null,
        ]);
    }

    /**
     * Indicate that the conversation is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_message_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }


