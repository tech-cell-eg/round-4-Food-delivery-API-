<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DishIngredient;
use App\Models\Dish;
use App\Models\Ingredient;

class DishIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishes = Dish::all();
        $ingredients = Ingredient::all();

        foreach ($dishes as $dish) {
            $dishIngredients = $this->getIngredientsForDish($dish, $ingredients);
            
            foreach ($dishIngredients as $ingredientId) {
                DishIngredient::create([
                    'dish_id' => $dish->id,
                    'ingredient_id' => $ingredientId,
                ]);
            }
        }
    }

    /**
     * Get appropriate ingredients for each dish
     */
    private function getIngredientsForDish($dish, $ingredients)
    {
        $dishName = strtolower($dish->name);
        $selectedIngredients = [];

        // Koshari ingredients
        if (stripos($dishName, 'koshari') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Rice', 'Lentils', 'Pasta', 'Onion', 'Tomatoes', 'Garlic', 'Cumin', 'Vegetable Oil'
            ]);
        }
        // Italian dishes
        elseif (stripos($dishName, 'carbonara') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Pasta', 'Eggs', 'Cheese', 'Black Pepper', 'Garlic', 'Olive Oil'
            ]);
        }
        elseif (stripos($dishName, 'pizza') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Flour', 'Tomatoes', 'Cheese', 'Basil', 'Olive Oil'
            ]);
        }
        // Grilled dishes
        elseif (stripos($dishName, 'grill') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Beef', 'Lamb', 'Chicken', 'Onion', 'Bell Peppers', 'Rice', 'Cumin', 'Paprika'
            ]);
        }
        // Seafood dishes
        elseif (stripos($dishName, 'fish') !== false || stripos($dishName, 'seafood') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Fish', 'Shrimp', 'Lemon Juice', 'Olive Oil', 'Garlic', 'Thyme', 'Salt'
            ]);
        }
        // Chicken dishes
        elseif (stripos($dishName, 'chicken') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Chicken', 'Rice', 'Onion', 'Garlic', 'Olive Oil', 'Salt', 'Black Pepper'
            ]);
        }
        // Breakfast dishes
        elseif (stripos($dishName, 'eggs') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Eggs', 'Butter', 'Bread', 'Milk'
            ]);
        }
        // Salad dishes
        elseif (stripos($dishName, 'salad') !== false) {
            $selectedIngredients = $this->getIngredientsByName($ingredients, [
                'Lettuce', 'Tomatoes', 'Cucumber', 'Olive Oil', 'Lemon Juice'
            ]);
        }
        // Default ingredients for other dishes
        else {
            $randomIngredients = $ingredients->random(rand(3, 6));
            $selectedIngredients = $randomIngredients->pluck('id')->toArray();
        }

        return $selectedIngredients;
    }

    /**
     * Get ingredient IDs by their names
     */
    private function getIngredientsByName($ingredients, $names)
    {
        $ids = [];
        foreach ($names as $name) {
            $ingredient = $ingredients->where('name', $name)->first();
            if ($ingredient) {
                $ids[] = $ingredient->id;
            }
        }
        return $ids;
    }
}
