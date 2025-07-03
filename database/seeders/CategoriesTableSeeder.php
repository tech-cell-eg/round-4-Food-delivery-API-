<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء فئات الأطعمة
        $categories = [
            ['name' => 'مشويات', 'image' => 'categories/grills.jpg', 'meal_type' => 'dinner'],
            ['name' => 'بيتزا', 'image' => 'categories/pizza.jpg', 'meal_type' => 'dinner'],
            ['name' => 'برجر', 'image' => 'categories/burger.jpg', 'meal_type' => 'lunch'],
            ['name' => 'سلطة', 'image' => 'categories/salads.jpg', 'meal_type' => 'lunch'],
            ['name' => 'حلويات', 'image' => 'categories/desserts.jpg', 'meal_type' => 'dinner'],
            ['name' => 'فطور', 'image' => 'categories/breakfast.jpg', 'meal_type' => 'breakfast'],
            ['name' => 'مأكولات بحرية', 'image' => 'categories/breakfast.jpg', 'meal_type' => 'lunch'],
            ['name' => 'مشروبات', 'image' => 'categories/drinks.jpg', 'meal_type' => 'breakfast'],
            ['name' => 'وجبات سريعة', 'image' => 'categories/drinks.jpg', 'meal_type' => 'breakfast'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
