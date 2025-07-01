<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // Basic Ingredients
            ['name' => 'Rice', 'type' => 'basic'],
            ['name' => 'Chicken', 'type' => 'basic'],
            ['name' => 'Beef', 'type' => 'basic'],
            ['name' => 'Lamb', 'type' => 'basic'],
            ['name' => 'Fish', 'type' => 'basic'],
            ['name' => 'Shrimp', 'type' => 'basic'],
            ['name' => 'Eggs', 'type' => 'basic'],
            ['name' => 'Milk', 'type' => 'basic'],
            ['name' => 'Cheese', 'type' => 'basic'],
            ['name' => 'Yogurt', 'type' => 'basic'],
            ['name' => 'Butter', 'type' => 'basic'],
            ['name' => 'Olive Oil', 'type' => 'basic'],
            ['name' => 'Vegetable Oil', 'type' => 'basic'],
            ['name' => 'Flour', 'type' => 'basic'],
            ['name' => 'Sugar', 'type' => 'basic'],
            ['name' => 'Salt', 'type' => 'basic'],
            ['name' => 'Black Pepper', 'type' => 'basic'],
            ['name' => 'Garlic', 'type' => 'basic'],
            ['name' => 'Onion', 'type' => 'basic'],
            ['name' => 'Ginger', 'type' => 'basic'],
            ['name' => 'Cumin', 'type' => 'basic'],
            ['name' => 'Coriander', 'type' => 'basic'],
            ['name' => 'Paprika', 'type' => 'basic'],
            ['name' => 'Cinnamon', 'type' => 'basic'],
            ['name' => 'Turmeric', 'type' => 'basic'],
            ['name' => 'Bay Leaves', 'type' => 'basic'],
            ['name' => 'Thyme', 'type' => 'basic'],
            ['name' => 'Basil', 'type' => 'basic'],
            ['name' => 'Parsley', 'type' => 'basic'],
            ['name' => 'Cilantro', 'type' => 'basic'],
            ['name' => 'Mint', 'type' => 'basic'],
            ['name' => 'Dill', 'type' => 'basic'],
            ['name' => 'Tomatoes', 'type' => 'basic'],
            ['name' => 'Potatoes', 'type' => 'basic'],
            ['name' => 'Carrots', 'type' => 'basic'],
            ['name' => 'Bell Peppers', 'type' => 'basic'],
            ['name' => 'Cucumber', 'type' => 'basic'],
            ['name' => 'Lettuce', 'type' => 'basic'],
            ['name' => 'Spinach', 'type' => 'basic'],
            ['name' => 'Cabbage', 'type' => 'basic'],
            ['name' => 'Eggplant', 'type' => 'basic'],
            ['name' => 'Zucchini', 'type' => 'basic'],
            ['name' => 'Green Beans', 'type' => 'basic'],
            ['name' => 'Peas', 'type' => 'basic'],
            ['name' => 'Corn', 'type' => 'basic'],
            ['name' => 'Mushrooms', 'type' => 'basic'],
            ['name' => 'Pasta', 'type' => 'basic'],
            ['name' => 'Bread', 'type' => 'basic'],
            ['name' => 'Tahini', 'type' => 'basic'],
            ['name' => 'Hummus', 'type' => 'basic'],
            ['name' => 'Fava Beans', 'type' => 'basic'],
            ['name' => 'Lentils', 'type' => 'basic'],
            ['name' => 'Chickpeas', 'type' => 'basic'],
            ['name' => 'Kidney Beans', 'type' => 'basic'],
            ['name' => 'Nuts', 'type' => 'basic'],
            ['name' => 'Almonds', 'type' => 'basic'],
            ['name' => 'Walnuts', 'type' => 'basic'],
            ['name' => 'Pine Nuts', 'type' => 'basic'],
            ['name' => 'Honey', 'type' => 'basic'],
            ['name' => 'Vinegar', 'type' => 'basic'],
            ['name' => 'Lemon Juice', 'type' => 'basic'],
            ['name' => 'Tomato Paste', 'type' => 'basic'],
            ['name' => 'Coconut Milk', 'type' => 'basic'],
            ['name' => 'Soy Sauce', 'type' => 'basic'],
            ['name' => 'Sesame Oil', 'type' => 'basic'],
            ['name' => 'Vanilla', 'type' => 'basic'],
            ['name' => 'Chocolate', 'type' => 'basic'],

            // Fruits
            ['name' => 'Apples', 'type' => 'fruit'],
            ['name' => 'Bananas', 'type' => 'fruit'],
            ['name' => 'Oranges', 'type' => 'fruit'],
            ['name' => 'Lemons', 'type' => 'fruit'],
            ['name' => 'Limes', 'type' => 'fruit'],
            ['name' => 'Grapes', 'type' => 'fruit'],
            ['name' => 'Strawberries', 'type' => 'fruit'],
            ['name' => 'Blueberries', 'type' => 'fruit'],
            ['name' => 'Raspberries', 'type' => 'fruit'],
            ['name' => 'Blackberries', 'type' => 'fruit'],
            ['name' => 'Mangoes', 'type' => 'fruit'],
            ['name' => 'Pineapple', 'type' => 'fruit'],
            ['name' => 'Kiwi', 'type' => 'fruit'],
            ['name' => 'Papaya', 'type' => 'fruit'],
            ['name' => 'Watermelon', 'type' => 'fruit'],
            ['name' => 'Cantaloupe', 'type' => 'fruit'],
            ['name' => 'Peaches', 'type' => 'fruit'],
            ['name' => 'Pears', 'type' => 'fruit'],
            ['name' => 'Plums', 'type' => 'fruit'],
            ['name' => 'Cherries', 'type' => 'fruit'],
            ['name' => 'Apricots', 'type' => 'fruit'],
            ['name' => 'Figs', 'type' => 'fruit'],
            ['name' => 'Dates', 'type' => 'fruit'],
            ['name' => 'Pomegranate', 'type' => 'fruit'],
            ['name' => 'Avocado', 'type' => 'fruit'],
            ['name' => 'Coconut', 'type' => 'fruit'],
        ];

        foreach ($ingredients as $ingredientData) {
            Ingredient::create($ingredientData);
        }
    }
}
