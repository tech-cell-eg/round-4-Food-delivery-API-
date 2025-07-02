<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدمين للعملاء
        $user1 = User::create([
            'name' => 'مستخدم تجريبي',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'type' => 'customer',
        ]);

        Customer::create([
            'id' => $user1->id,
            'preferred_payment_method' => 'credit_card',
        ]);

        $user2 = User::create([
            'name' => 'عميل آخر',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'type' => 'customer',
        ]);

        Customer::create([
            'id' => $user2->id,
            'preferred_payment_method' => 'cash_on_delivery',
        ]);

        // إنشاء مستخدمين للطهاة
        $user3 = User::create([
            'name' => 'طاهي تجريبي',
            'email' => 'chef@example.com',
            'password' => Hash::make('password'),
            'type' => 'chef',
        ]);

        $user4 = User::create([
            'name' => 'طاهي آخر',
            'email' => 'chef2@example.com',
            'password' => Hash::make('password'),
            'type' => 'chef',
        ]);

        // إنشاء مستخدم للمسؤول
        User::create([
            'name' => 'المسؤول',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'type' => 'customer', // نستخدم customer لأن admin غير موجود في الـ enum
        ]);
    }
}
