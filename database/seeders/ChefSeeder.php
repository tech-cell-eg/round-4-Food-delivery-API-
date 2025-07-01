<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\User;

class ChefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefUsers = User::where('type', 'chef')->get();

        $chefData = [
            [
                'national_id' => '29501012345678',
                'balance' => 2500.75,
                'description' => 'Specializing in authentic Egyptian dishes with over 12 years of experience. Known for perfect koshari and traditional stews.',
                'stripe_account_id' => 'acct_1234567890',
                'location' => 'Maadi, Cairo, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '28612023456789',
                'balance' => 3200.50,
                'description' => 'Expert in Lebanese and Mediterranean cuisine. Famous for outstanding mezze platters and grilled specialties.',
                'stripe_account_id' => 'acct_2345678901',
                'location' => 'Zamalek, Cairo, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '29003034567890',
                'balance' => 1850.25,
                'description' => 'Italian cuisine master with certification from Culinary Institute of Italy. Pasta and risotto specialist.',
                'stripe_account_id' => 'acct_3456789012',
                'location' => 'New Cairo, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '29204045678901',
                'balance' => 2100.00,
                'description' => 'Award-winning pastry chef and dessert artist. Creates stunning fusion desserts blending East and West.',
                'stripe_account_id' => 'acct_4567890123',
                'location' => 'Heliopolis, Cairo, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '28805056789012',
                'balance' => 2950.80,
                'description' => 'BBQ and grilling specialist with expertise in Asian marinades and American smoking techniques.',
                'stripe_account_id' => 'acct_5678901234',
                'location' => 'Sheikh Zayed, Giza, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '29106067890123',
                'balance' => 1750.40,
                'description' => 'Health-conscious chef focusing on organic, locally-sourced ingredients. Keto and Mediterranean diet specialist.',
                'stripe_account_id' => 'acct_6789012345',
                'location' => 'Dokki, Giza, Egypt',
                'is_verified' => false,
            ],
            [
                'national_id' => '28507078901234',
                'balance' => 3500.90,
                'description' => 'Seafood expert with extensive knowledge of Red Sea and Mediterranean catches. Fresh fish daily.',
                'stripe_account_id' => 'acct_7890123456',
                'location' => 'Hurghada, Red Sea, Egypt',
                'is_verified' => true,
            ],
            [
                'national_id' => '29308089012345',
                'balance' => 2200.60,
                'description' => 'Traditional Egyptian home cooking with modern presentation. Comfort food that reminds you of grandma\'s kitchen.',
                'stripe_account_id' => 'acct_8901234567',
                'location' => 'Mohandessin, Giza, Egypt',
                'is_verified' => false,
            ],
        ];

        foreach ($chefUsers as $index => $user) {
            if (isset($chefData[$index])) {
                Chef::create([
                    'id' => $user->id,
                    'national_id' => $chefData[$index]['national_id'],
                    'balance' => $chefData[$index]['balance'],
                    'description' => $chefData[$index]['description'],
                    'stripe_account_id' => $chefData[$index]['stripe_account_id'],
                    'location' => $chefData[$index]['location'],
                    'is_verified' => $chefData[$index]['is_verified'],
                ]);
            }
        }
    }
}
