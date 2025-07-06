<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(50)->create()->each(function ($order) {
            $orderItemsCount = rand(1, 4);
            
            for ($i = 0; $i < $orderItemsCount; $i++) {
                $order->orderItems()->create([
                    'dish_id' => \App\Models\Dish::inRandomOrder()->first()->id,
                    'dish_name' => \App\Models\Dish::inRandomOrder()->first()->name,
                    'size_name' => ['small', 'medium', 'large'][rand(0, 2)],
                    'quantity' => rand(1, 3),
                    'unit_price' => rand(10, 100),
                    'total_price' => rand(10, 100) * rand(1, 3),
                ]);
            }
            
            $subtotal = $order->orderItems->sum('total_price');
            $deliveryFee = rand(10, 30);
            $tax = $subtotal * 0.15;
            $discount = rand(0, $subtotal * 0.2);
            $total = $subtotal + $deliveryFee + $tax - $discount;
            
            $order->update([
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
            ]);
        });
    }
} 