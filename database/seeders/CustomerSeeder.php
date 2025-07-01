<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('type', 'customer')
            ->whereDoesntHave('customer')
            ->each(function ($user) {
                Customer::factory()
                    ->forUser($user)
                    ->create();
            });
    }
}
