<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['text', 'voice']);
        
        return [
            'conversation_id' => Conversation::factory(),
            'sender_id' => User::factory(),
            'type' => $type,
            'content' => $type === 'text' 
                ? $this->faker->sentence(random_int(3, 20))
                : 'voice_message_' . $this->faker->uuid . '.mp3',
            'seen_at' => $this->faker->boolean(70) 
                ? $this->faker->dateTimeBetween('-1 week', 'now')
                : null,
        ];
    }

    /**
     * Indicate that the message is a text message.
     */
    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'text',
            'content' => $this->faker->sentence(random_int(3, 20)),
        ]);
    }

    /**
     * Indicate that the message is a voice message.
     */
    public function voice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'voice',
            'content' => 'voice_message_' . $this->faker->uuid . '.mp3',
        ]);
    }

    /**
     * Indicate that the message is unread.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'seen_at' => null,
        ]);
    }

    /**
     * Indicate that the message is read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'seen_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the message is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }
} 