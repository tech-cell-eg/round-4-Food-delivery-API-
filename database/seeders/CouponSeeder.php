<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\Chef;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefs = Chef::all();

        $coupons = [
            // Welcome discount coupons
            [
                'code' => 'WELCOME20',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'description' => 'Welcome offer! Get 20% off on your first order from this chef.',
                'expires_at' => Carbon::now()->addMonths(3),
            ],
            [
                'code' => 'NEWCUSTOMER15',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'description' => 'New customer special - 15% discount on all menu items.',
                'expires_at' => Carbon::now()->addMonths(2),
            ],

            // Fixed amount discount coupons
            [
                'code' => 'SAVE50',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'description' => 'Save 50 EGP on orders above 200 EGP.',
                'expires_at' => Carbon::now()->addWeeks(6),
            ],
            [
                'code' => 'FLAT30',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 30.00,
                'description' => 'Flat 30 EGP discount on any order.',
                'expires_at' => Carbon::now()->addMonth(),
            ],

            // Weekend specials
            [
                'code' => 'WEEKEND25',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'description' => 'Weekend special! 25% off on Friday and Saturday orders.',
                'expires_at' => Carbon::now()->addWeeks(8),
            ],
            [
                'code' => 'FRIDAY100',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 100.00,
                'description' => 'Friday mega deal - 100 EGP off on orders above 500 EGP.',
                'expires_at' => Carbon::now()->addWeeks(4),
            ],

            // Loyalty coupons
            [
                'code' => 'LOYAL10',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'description' => 'Loyalty reward for returning customers - 10% off.',
                'expires_at' => Carbon::now()->addMonths(6),
            ],
            [
                'code' => 'VIP30',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 30.00,
                'description' => 'VIP customer exclusive - 30% discount on premium dishes.',
                'expires_at' => Carbon::now()->addMonths(4),
            ],

            // Special occasion coupons
            [
                'code' => 'RAMADAN20',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'description' => 'Ramadan special offer - 20% off on traditional dishes.',
                'expires_at' => Carbon::now()->addMonths(2),
            ],
            [
                'code' => 'EID150',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 150.00,
                'description' => 'Eid celebration discount - 150 EGP off on family meals.',
                'expires_at' => Carbon::now()->addWeeks(3),
            ],

            // Limited time offers
            [
                'code' => 'FLASH35',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 35.00,
                'description' => 'Flash sale! 35% off for the next 48 hours only.',
                'expires_at' => Carbon::now()->addDays(2),
            ],
            [
                'code' => 'MIDNIGHT40',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 40.00,
                'description' => 'Midnight madness - 40% off on late night orders.',
                'expires_at' => Carbon::now()->addWeeks(2),
            ],

            // Cuisine-specific coupons
            [
                'code' => 'ITALIAN25',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'description' => 'Italian cuisine lover discount - 25% off on pasta and pizza.',
                'expires_at' => Carbon::now()->addMonths(3),
            ],
            [
                'code' => 'SEAFOOD75',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 75.00,
                'description' => 'Fresh seafood special - 75 EGP off on seafood dishes.',
                'expires_at' => Carbon::now()->addWeeks(5),
            ],

            // Healthy options coupons
            [
                'code' => 'HEALTHY15',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'description' => 'Healthy choice discount - 15% off on diet-friendly meals.',
                'expires_at' => Carbon::now()->addMonths(4),
            ],
            [
                'code' => 'KETO50',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'description' => 'Keto diet special - 50 EGP off on keto-friendly dishes.',
                'expires_at' => Carbon::now()->addWeeks(6),
            ],

            // Bulk order discounts
            [
                'code' => 'BULK200',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 200.00,
                'description' => 'Bulk order discount - 200 EGP off on orders above 1000 EGP.',
                'expires_at' => Carbon::now()->addMonths(5),
            ],
            [
                'code' => 'PARTY30',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 30.00,
                'description' => 'Party catering special - 30% off on large orders.',
                'expires_at' => Carbon::now()->addWeeks(8),
            ],

            // Student discounts
            [
                'code' => 'STUDENT12',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 12.00,
                'description' => 'Student discount - 12% off with valid student ID.',
                'expires_at' => Carbon::now()->addMonths(12),
            ],

            // Early bird specials
            [
                'code' => 'EARLYBIRD20',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'description' => 'Early bird special - 20% off on orders placed before 11 AM.',
                'expires_at' => Carbon::now()->addMonths(2),
            ],

            // Expired coupons for testing
            [
                'code' => 'EXPIRED50',
                'chef_id' => $chefs->random()->id,
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'description' => 'This coupon has expired and should not be usable.',
                'expires_at' => Carbon::now()->subWeeks(2),
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
