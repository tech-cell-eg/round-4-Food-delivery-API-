<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefUsers = [
            [
                'name' => 'Tiba Grill',
                'email' => 'contact@tibagrill.com',
                'password' => Hash::make('password123'),
                'phone' => '+201012345678',
                'profile_image' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=300',
                'bio' => 'Serving authentic Egyptian grilled dishes with a modern twist in a cozy setting.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Beirut Bites',
                'email' => 'info@beirutbites.com',
                'password' => Hash::make('password123'),
                'phone' => '+201123456789',
                'profile_image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=300',
                'bio' => 'Lebanese street food and homemade meals, delivering fresh flavors daily.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pasta Roma',
                'email' => 'hello@pastaroma.com',
                'password' => Hash::make('password123'),
                'phone' => '+201234567890',
                'profile_image' => 'https://images.unsplash.com/photo-1523986371872-9d3ba2e2f642?w=300',
                'bio' => 'Italian restaurant known for handmade pasta and rich sauces in downtown Cairo.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sweet Delight',
                'email' => 'support@sweetdelight.com',
                'password' => Hash::make('password123'),
                'phone' => '+201345678901',
                'profile_image' => 'https://images.unsplash.com/photo-1589308078056-8329e52baed9?w=300',
                'bio' => 'Dessert and bakery shop offering classic treats and creative fusions.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Smoky House',
                'email' => 'bbq@smokyhouse.com',
                'password' => Hash::make('password123'),
                'phone' => '+201456789012',
                'profile_image' => 'https://images.unsplash.com/photo-1600891963920-b3718363fadb?w=300',
                'bio' => 'Home of premium BBQ with both American and Asian barbecue flavors.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Green Plate',
                'email' => 'info@greenplate.com',
                'password' => Hash::make('password123'),
                'phone' => '+201567890123',
                'profile_image' => 'https://images.unsplash.com/photo-1556910103-1c72fb40e0e3?w=300',
                'bio' => 'Healthy food made delicious using organic ingredients and balanced recipes.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sea Treasure',
                'email' => 'order@seatreasure.com',
                'password' => Hash::make('password123'),
                'phone' => '+201678901234',
                'profile_image' => 'https://images.unsplash.com/photo-1601312370823-cd3ac9338f52?w=300',
                'bio' => 'Specializing in fresh seafood dishes inspired by Mediterranean and Red Sea flavors.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Om El Donia Kitchen',
                'email' => 'info@omeldonia.com',
                'password' => Hash::make('password123'),
                'phone' => '+201789012345',
                'profile_image' => 'https://images.unsplash.com/photo-1627843563095-7c74b711bf98?w=300',
                'bio' => 'Reviving Egyptian home-style cooking with a modern presentation.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
        ];


        foreach ($chefUsers as $userData) {
            User::create($userData);
        }
    }
}
