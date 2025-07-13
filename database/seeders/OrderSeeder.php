<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Chef;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Dish;
use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Chef::count() == 0 || Customer::count() == 0 || Dish::count() == 0) {
            return;
        }

        $chefs = Chef::all();
        $customers = Customer::all();
        $dishes = Dish::all();
        $coupons = Coupon::all();

        $this->createOrdersForPeriods($chefs, $customers, $dishes, $coupons);
    }

    private function createOrdersForPeriods($chefs, $customers, $dishes, $coupons)
    {
        $now = Carbon::now();

        $this->createOrdersForDate($chefs, $customers, $dishes, $coupons, $now, 5, 'today');

        $lastMonth = $now->copy()->subMonth();
        $this->createOrdersForMonth($chefs, $customers, $dishes, $coupons, $lastMonth, 15, 'last month');

        $lastYear = $now->copy()->subYear();
        $this->createOrdersForYear($chefs, $customers, $dishes, $coupons, $lastYear, 30, 'last year');
    }

    private function createOrdersForDate($chefs, $customers, $dishes, $coupons, $date, $count, $period)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->createOrder($chefs, $customers, $dishes, $coupons, $date);
        }
    }

    private function createOrdersForMonth($chefs, $customers, $dishes, $coupons, $month, $count, $period)
    {

        for ($i = 0; $i < $count; $i++) {
            $randomDay = rand(1, $month->daysInMonth);
            $orderDate = $month->copy()->day($randomDay);
            $this->createOrder($chefs, $customers, $dishes, $coupons, $orderDate);
        }
    }

    private function createOrdersForYear($chefs, $customers, $dishes, $coupons, $year, $count, $period)
    {

        for ($i = 0; $i < $count; $i++) {
            $randomMonth = rand(1, 12);
            $randomDay = rand(1, 28);
            $orderDate = $year->copy()->month($randomMonth)->day($randomDay);
            $this->createOrder($chefs, $customers, $dishes, $coupons, $orderDate);
        }
    }

    private function createOrder($chefs, $customers, $dishes, $coupons, $date)
    {
        $chef = $chefs->random();
        $customer = $customers->random();

        $address = $customer->addresses()->first();
        if (!$address) {
            $address = Address::factory()->create(['customer_id' => $customer->id]);
        }

        $subtotal = fake()->randomFloat(2, 50, 500);
        $deliveryFee = fake()->randomFloat(2, 10, 50);
        $tax = $subtotal * 0.15;
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $total = $subtotal + $deliveryFee + $tax - $discount;

        $order = Order::create([
            'customer_id' => $customer->id,
            'chef_id' => $chef->id,
            'address_id' => $address->id,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'coupon_id' => $coupons->isNotEmpty() ? (fake()->optional(0.3)->randomElement($coupons))?->id : null,
            'status' => fake()->randomElement(['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled']),
            'order_number' => Order::genNumber(),
            'notes' => fake()->optional()->sentence(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $itemCount = rand(1, 5);
        $chefDishes = $dishes->where('chef_id', $chef->id);

        if ($chefDishes->isEmpty()) {
            $chefDishes = $dishes->random(min($itemCount, $dishes->count()));
        } else {
            $chefDishes = $chefDishes->random(min($itemCount, $chefDishes->count()));
        }

        foreach ($chefDishes as $dish) {
            $quantity = rand(1, 3);
            $unitPrice = fake()->randomFloat(2, 10, 100);
            $totalPrice = $quantity * $unitPrice;

            OrderItem::create([
                'order_id' => $order->id,
                'dish_id' => $dish->id,
                'size' => fake()->randomElement(['small', 'medium', 'large']),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
