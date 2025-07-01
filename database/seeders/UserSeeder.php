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
                'name' => 'Omar Khaled',
                'email' => 'omar.khaled@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201234567890',
                'profile_image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=300',
                'bio' => 'Italian cuisine expert and pasta specialist with international training.',
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
                'name' => 'Hassan Ali',
                'email' => 'hassan.ali@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201678901234',
                'profile_image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=300',
                'bio' => 'Seafood specialist with expertise in Mediterranean and Red Sea cuisine.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nadia Ibrahim',
                'email' => 'nadia.ibrahim@chef.com',
                'password' => Hash::make('password123'),
                'phone' => '+201789012345',
                'profile_image' => 'https://images.unsplash.com/photo-1595273670150-bd0c3c392e46?w=300',
                'bio' => 'Traditional Egyptian home cooking with a modern presentation style.',
                'type' => 'chef',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($chefUsers as $userData) {
            User::create($userData);
        }
    }
}
