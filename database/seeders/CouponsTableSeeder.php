<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء كوبونات خصم
        Coupon::create([
            'code' => 'WELCOME10',
            'chef_id' => 3,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'description' => 'خصم 10% للعملاء الجدد',
            'expires_at' => Carbon::now()->addMonths(1),
        ]);

        Coupon::create([
            'code' => 'SUMMER20',
            'chef_id' => 3,
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'description' => 'خصم صيفي 20%',
            'expires_at' => Carbon::now()->addDays(15),
        ]);

        Coupon::create([
            'code' => 'FIXED50',
            'chef_id' => 4,
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'description' => 'خصم ثابت 50 ريال',
            'expires_at' => Carbon::now()->addMonths(2),
        ]);

        // كوبون منتهي الصلاحية
        Coupon::create([
            'code' => 'EXPIRED25',
            'chef_id' => 4,
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'description' => 'كوبون منتهي الصلاحية',
            'expires_at' => Carbon::now()->subDays(5),
        ]);
    }
}
