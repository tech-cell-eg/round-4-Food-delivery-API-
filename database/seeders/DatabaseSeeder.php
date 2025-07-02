<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CartSeeder;
use Database\Seeders\CartItemSeeder;
use Database\Seeders\AddressSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order to maintain foreign key relationships
        $this->call([
            UserSeeder::class,          // Create users first (for chefs and customers)
            CategorySeeder::class,      // Create categories
            IngredientSeeder::class,    // Create ingredients
            ChefSeeder::class,          // Create chef profiles (depends on users)
            CustomerSeeder::class,      // Create customers (depends on users)
            AddressSeeder::class,       // Create addresses (depends on customers)
            DishSeeder::class,          // Create dishes (depends on chefs and categories)
            DishSizeSeeder::class,      // Create dish sizes (depends on dishes)
            DishIngredientSeeder::class, // Link dishes with ingredients (depends on dishes and ingredients)
            CartSeeder::class,          // Create carts (depends on users and customers)
            CartItemSeeder::class,      // Create cart items (depends on carts and dishes)
            CouponSeeder::class,        // Create coupons (depends on chefs)
            ReviewsTableSeeder::class,  // Create reviews (depends on users and dishes)
        ]);
    }
}
