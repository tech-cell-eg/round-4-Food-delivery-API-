<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Breakfast Categories
            [
                'name' => 'Traditional Breakfast',
                'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=400',
                'meal_type' => 'breakfast',
            ],
            [
                'name' => 'Healthy Breakfast',
                'image' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=400',
                'meal_type' => 'breakfast',
            ],
            [
                'name' => 'Continental Breakfast',
                'image' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400',
                'meal_type' => 'breakfast',
            ],
            [
                'name' => 'Pastries & Sweets',
                'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400',
                'meal_type' => 'breakfast',
            ],

            // Lunch Categories
            [
                'name' => 'Egyptian Cuisine',
                'image' => 'https://images.unsplash.com/photo-1544807976-9b2b24998552?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Italian Cuisine',
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Lebanese & Middle Eastern',
                'image' => 'https://images.unsplash.com/photo-1512852939750-1305098529bf?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Grilled & BBQ',
                'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Seafood',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Healthy & Diet',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Asian Cuisine',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03246963d7d3?w=400',
                'meal_type' => 'lunch',
            ],
            [
                'name' => 'Sandwiches & Wraps',
                'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400',
                'meal_type' => 'lunch',
            ],

            // Dinner Categories
            [
                'name' => 'Fine Dining',
                'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400',
                'meal_type' => 'dinner',
            ],
            [
                'name' => 'Traditional Egyptian',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400',
                'meal_type' => 'dinner',
            ],
            [
                'name' => 'International Cuisine',
                'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400',
                'meal_type' => 'dinner',
            ],
            [
                'name' => 'Comfort Food',
                'image' => 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=400',
                'meal_type' => 'dinner',
            ],
            [
                'name' => 'Light Dinner',
                'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400',
                'meal_type' => 'dinner',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
