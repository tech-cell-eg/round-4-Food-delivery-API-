<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Dish;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        
        foreach ($orders as $order) {
            $orderItemsCount = rand(1, 4);
            
            for ($i = 0; $i < $orderItemsCount; $i++) {
                $dish = Dish::inRandomOrder()->first();
                $quantity = rand(1, 3);
                $unitPrice = rand(10, 100);
                $totalPrice = $quantity * $unitPrice;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $dish->id,
                    'dish_name' => $dish->name,
                    'size_name' => ['small', 'medium', 'large'][rand(0, 2)],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }
        }
        
        OrderItem::factory(100)->create();
    }
} 