<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chef_id' => \App\Models\Chef::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(400, 400, 'food'),
            'is_available' => $this->faker->boolean(90),
            'total_rate' => $this->faker->numberBetween(0, 500),
            'avg_rate' => $this->faker->randomFloat(1, 1, 5),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}
