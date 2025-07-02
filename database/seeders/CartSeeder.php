<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * تشغيل الـ seeder
     *
     * @return void
     */
    public function run()
    {
        // حذف البيانات الموجودة مسبقاً لتجنب التكرار
        DB::table('carts')->truncate();

        // الحصول على العملاء (المستخدمين من نوع customer)
        $customers = User::where('type', 'customer')->get();

        // التأكد من وجود عملاء
        if ($customers->isEmpty()) {
            $this->command->warn('تحذير: يجب أن تحتوي قاعدة البيانات على عملاء قبل تشغيل هذا الـ seeder.');
            return;
        }

        // مصفوفة لتخزين سلات التسوق
        $carts = [];

        // إنشاء سلة تسوق لكل عميل
        foreach ($customers as $customer) {
            // 70% فرصة لإنشاء سلة تسوق نشطة للعميل
            if (rand(1, 10) <= 7) {
                $carts[] = [
                    'customer_id' => $customer->id,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // إدخال البيانات في قاعدة البيانات
        DB::table('carts')->insert($carts);

        $this->command->info('تم إنشاء ' . count($carts) . ' سلة تسوق بنجاح.');
    }
}
