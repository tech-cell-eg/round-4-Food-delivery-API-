<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Dish;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemSeeder extends Seeder
{
    /**
     * تشغيل الـ seeder
     *
     * @return void
     */
    public function run()
    {
        // حذف البيانات الموجودة مسبقاً لتجنب التكرار
        DB::table('cart_items')->truncate();

        // الحصول على عربات التسوق والأطباق المتاحة
        $carts = Cart::all();
        $dishes = Dish::all();

        // التأكد من وجود بيانات كافية
        if ($carts->isEmpty() || $dishes->isEmpty()) {
            $this->command->warn('تحذير: يجب أن تحتوي قاعدة البيانات على عربات تسوق وأطباق قبل تشغيل هذا الـ seeder.');
            return;
        }

        // مصفوفة لتخزين عناصر السلة
        $cartItems = [];

        // إنشاء عناصر سلة لكل عربة تسوق
        foreach ($carts as $cart) {
            // عدد عشوائي من العناصر لكل سلة (بين 1 و 5 عناصر)
            $itemCount = rand(1, 5);
            
            // اختيار أطباق عشوائية
            $selectedDishes = $dishes->random($itemCount);
            
            foreach ($selectedDishes as $dish) {
                $quantity = rand(1, 5);
                $size = $this->getRandomSize();
                $price = $this->calculatePrice($dish->price, $size);
                
                $cartItems[] = [
                    'cart_id' => $cart->id,
                    'dish_id' => $dish->id,
                    'size' => $size,
                    'price' => $price,
                    'quantity' => $quantity,
                    'notes' => rand(0, 1) ? 'ملاحظات إضافية للطلب' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // إدخال البيانات في قاعدة البيانات
        DB::table('cart_items')->insert($cartItems);
        
        $this->command->info('تم إنشاء ' . count($cartItems) . ' عنصر سلة بنجاح.');
    }

    /**
     * الحصول على حجم عشوائي للطبق
     *
     * @return string
     */
    private function getRandomSize()
    {
        $sizes = ['small', 'medium', 'large'];
        return $sizes[array_rand($sizes)];
    }

    /**
     * حساب السعر بناءً على السعر الأساسي والحجم
     *
     * @param float $basePrice
     * @param string $size
     * @return float
     */
    private function calculatePrice($basePrice, $size)
    {
        $multipliers = [
            'small' => 0.8,
            'medium' => 1.0,
            'large' => 1.2,
        ];

        return $basePrice * $multipliers[$size];
    }
}
