<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $customer = User::where('type', 'customer')->inRandomOrder()->first();
        $chef = User::where('type', 'chef')->inRandomOrder()->first();
        $dish = $chef->dish()->inRandomOrder()->first();



        return [
            'customer_id' => $customer->id,
            'chef_id' => $chef->id,
            'dish_id' => $dish->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),

        ];
    }


    public function rating(int $rating)
    {
        return $this->state(function (array $attributes) use ($rating) {
            return [
                'rating' => $rating,
            ];
        });
    }

    public function positive()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(4, 5),
                'comment' => $this->faker->randomElement([
                    'Excellent service!',
                    'Amazing food quality!',
                    'Will definitely order again!',
                    'Perfect experience from start to finish.',
                ]),
            ];
        });
    }


    public function negative()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(1, 2),
                'comment' => $this->faker->randomElement([
                    'Disappointing experience.',
                    'Food was not as described.',
                    'Late delivery.',
                    'Poor communication.',
                ]),
            ];
        });
    }
}
