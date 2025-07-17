<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order to maintain foreign key relationships
        $this->call([
            UserSeeder::class,          // Create users first (for chefs)
            CategorySeeder::class,      // Create categories
            IngredientSeeder::class,    // Create ingredients
            ChefSeeder::class,          // Create chef profiles (depends on users)
            CustomerSeeder::class,      // Create customers (depends on users)
            AddressesTableSeeder::class, // Create addresses (depends on customers)
            ConversationsTableSeeder::class, // Create conversations (depends on chefs and customers)
            MessagesTableSeeder::class, // Create messages (depends on conversations and users)
            DishSeeder::class,          // Create dishes (depends on chefs and categories)
            DishSizeSeeder::class,      // Create dish sizes (depends on dishes)
            DishIngredientSeeder::class, // Link dishes with ingredients (depends on dishes and ingredients)
            CouponSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
