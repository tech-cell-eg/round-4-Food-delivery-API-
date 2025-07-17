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
        User::factory()
            ->count(10)
            ->chef()
            ->create();

        User::factory()
            ->count(10)
            ->customer()
            ->create();
        $chefUsers = [
            [
                'name' => 'Tiba Grill',
                'email' => 'contact@tibagrill.com',
                'password' => Hash::make('password123'),
                'phone' => '+201012345678',
                'profile_image' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=300',
                'bio' => 'Serving authentic Egyptian grilled dishes with a modern twist in a cozy setting.',

                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201012345678',
                'profile_image' => 'https://images.unsplash.com/photo-1583394293214-28a1386e5ebb?w=300',
                'bio' => 'Experienced Egyptian chef specializing in traditional Middle Eastern cuisine with modern twists.',
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
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fatima Al-Zahra',
                'email' => 'fatima.alzahra@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201123456789',
                'profile_image' => 'https://images.unsplash.com/photo-1566554273541-37a9ca77b91b?w=300',
                'bio' => 'Master chef with 15 years of experience in Mediterranean and Lebanese cuisine.',
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
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Omar Khaled',
                'email' => 'omar.khaled@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201234567890',
                'profile_image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=300',
                'bio' => 'Italian cuisine expert and pasta specialist with international training.',
                'type' => 'customer',
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
                'name' => 'Yasmin Mohamed',
                'email' => 'yasmin.mohamed@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201345678901',
                'profile_image' => 'https://images.unsplash.com/photo-1607631568010-a87245c0daf8?w=300',
                'bio' => 'Dessert and bakery specialist, known for fusion desserts combining Eastern and Western flavors.',
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
                'name' => 'Karim Abdullah',
                'email' => 'karim.abdullah@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201456789012',
                'profile_image' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=300',
                'bio' => 'BBQ and grilling expert, specializing in Asian and American barbecue styles.',
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
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mona Saeed',
                'email' => 'mona.saeed@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201567890123',
                'profile_image' => 'https://images.unsplash.com/photo-1594736797933-d0401ba552fe?w=300',
                'bio' => 'Healthy cuisine chef focusing on organic ingredients and diet-friendly meals.',
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

                'name' => 'Hassan Ali',
                'email' => 'hassan.ali@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201678901234',
                'profile_image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=300',
                'bio' => 'Seafood specialist with expertise in Mediterranean and Red Sea cuisine.',

                'type' => 'customer',
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
