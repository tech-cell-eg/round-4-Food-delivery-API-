<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Dish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Order::count() == 0 || Dish::count() == 0) {
            return;
        }

        $orders = Order::all();
        $dishes = Dish::all();


        foreach ($orders->take(10) as $order) {
            if (!$order->chef_id) {
                continue;
            }

            $additionalItems = rand(1, 3);

            $chefDishes = $dishes->where('chef_id', $order->chef_id);

            if ($chefDishes->isEmpty()) {
                $chefDishes = $dishes;
            }

            for ($i = 0; $i < $additionalItems; $i++) {
                $dish = $chefDishes->random();
                $quantity = rand(1, 4);
                $unitPrice = fake()->randomFloat(2, 15, 120);
                $totalPrice = $quantity * $unitPrice;

                $existingItem = OrderItem::where('order_id', $order->id)
                                        ->where('dish_id', $dish->id)
                                        ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $quantity,
                        'total_price' => $existingItem->total_price + $totalPrice,
                    ]);
                } else {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'dish_id' => $dish->id,
                        'size' => fake()->randomElement(['small', 'medium', 'large']),
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);

                    $order->increment('subtotal', $totalPrice);
                    $order->increment('total', $totalPrice);
                }
            }
        }

    }
}
