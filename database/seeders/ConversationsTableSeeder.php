<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\Chef;
use App\Models\Customer;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefs = Chef::all();
        $customers = Customer::all();

        if ($chefs->isEmpty() || $customers->isEmpty()) {
            
            Conversation::factory()
                ->count(20)
                ->create();

            Conversation::factory()
                ->count(10)
                ->recent()
                ->create();

            Conversation::factory()
                ->count(5)
                ->withoutMessages()
                ->create();
        } else {
            $chefs->take(10)->each(function ($chef) use ($customers) {
                $randomCustomers = $customers->random(rand(1, 3));
                
                $randomCustomers->each(function ($customer) use ($chef) {
                    Conversation::factory()->create([
                        'chef_id' => $chef->id,
                        'customer_id' => $customer->id,
                    ]);
                });
            });

            Conversation::factory()
                ->count(15)
                ->create();
        }

    }
} 
