<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // تشغيل البذور بالترتيب الصحيح
        $this->call([
            UsersTableSeeder::class,
            ChefsTableSeeder::class,
            CategoriesTableSeeder::class,
            AddressesTableSeeder::class,
            DishesTableSeeder::class,
            CouponsTableSeeder::class,
        ]);
    }
}
