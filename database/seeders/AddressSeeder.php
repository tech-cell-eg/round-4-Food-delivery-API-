<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    /**
     * تشغيل عملية إنشاء عناوين للعملاء.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // الحصول على جميع المستخدمين من نوع customer
        $customers = \App\Models\User::where('type', 'customer')->get();

        if ($customers->isEmpty()) {
            $this->command->warn('لا يوجد عملاء متاحين. يرجى تشغيل CustomerSeeder أولاً.');
            return;
        }

        $addresses = [];

        foreach ($customers as $customer) {
            // إنشاء عنوان افتراضي لكل عميل
            $isDefault = rand(0, 1) ? true : false;

            $addresses[] = [
                'customer_id' => $customer->id,
                'post_code' => rand(10000, 99999),
                'address_text' => $faker->address,
                'street' => $faker->streetName,
                'appartment' => $faker->randomNumber(3),
                'lable' => 'المنزل',
                'is_default' => $isDefault,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // إنشاء عناوين إضافية لبعض العملاء (0-2 عنوان إضافي)
            $extraAddresses = rand(0, 2);
            $labels = ['العمل', 'الوالدين', 'صديق', 'أخرى'];

            for ($i = 0; $i < $extraAddresses; $i++) {
                $addresses[] = [
                    'customer_id' => $customer->id,
                    'post_code' => rand(10000, 99999),
                    'address_text' => $faker->address,
                    'street' => $faker->streetName,
                    'appartment' => $faker->randomNumber(3),
                    'lable' => $labels[array_rand($labels)],
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // إدخال العناوين في قاعدة البيانات
        Address::insert($addresses);

        $this->command->info('تم إنشاء ' . count($addresses) . ' عنوان بنجاح.');
    }
}
