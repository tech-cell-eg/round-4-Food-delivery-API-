<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * تشغيل عملية إنشاء بيانات العملاء.
     */
    public function run(): void
    {
        // إنشاء 10 عملاء
        $customers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '0501234567',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile1.jpg',
            ],
            [
                'name' => 'سارة علي',
                'email' => 'sara@example.com',
                'phone' => '0502345678',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile2.jpg',
            ],
            [
                'name' => 'محمد خالد',
                'email' => 'mohammed@example.com',
                'phone' => '0503456789',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile3.jpg',
            ],
            [
                'name' => 'فاطمة عبدالله',
                'email' => 'fatima@example.com',
                'phone' => '0504567890',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile4.jpg',
            ],
            [
                'name' => 'عمر سعيد',
                'email' => 'omar@example.com',
                'phone' => '0505678901',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile5.jpg',
            ],
            [
                'name' => 'نورة أحمد',
                'email' => 'noura@example.com',
                'phone' => '0506789012',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile6.jpg',
            ],
            [
                'name' => 'خالد محمد',
                'email' => 'khaled@example.com',
                'phone' => '0507890123',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile7.jpg',
            ],
            [
                'name' => 'هند سعود',
                'email' => 'hind@example.com',
                'phone' => '0508901234',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile8.jpg',
            ],
            [
                'name' => 'سلطان عبدالعزيز',
                'email' => 'sultan@example.com',
                'phone' => '0509012345',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile9.jpg',
            ],
            [
                'name' => 'منى إبراهيم',
                'email' => 'mona@example.com',
                'phone' => '0500123456',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'profile_image' => 'customers/profile10.jpg',
            ],
        ];

        foreach ($customers as $customer) {
            // التحقق من عدم وجود المستخدم مسبقاً
            $existingUser = User::where('email', $customer['email'])->first();
            
            if (!$existingUser) {
                // إنشاء المستخدم مع إضافة حقل remember_token
                User::create(array_merge($customer, [
                    'remember_token' => Str::random(10),
                ]));
            }
        }

        $this->command->info('تم إنشاء 10 عملاء بنجاح.');
    }
}
