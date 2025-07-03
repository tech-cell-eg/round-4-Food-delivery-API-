<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء عناوين للعملاء
        Address::create([
            'customer_id' => 1,
            'post_code' => 12345,
            'address_text' => 'شارع الملك فهد، حي الرياض',
            'street' => 'الملك فهد',
            'appartment' => 5,
            'lable' => 'المنزل',
            'is_default' => true,
        ]);

        Address::create([
            'customer_id' => 1,
            'post_code' => 54321,
            'address_text' => 'شارع العليا، حي السليمانية',
            'street' => 'العليا',
            'appartment' => 10,
            'lable' => 'العمل',
            'is_default' => false,
        ]);

        Address::create([
            'customer_id' => 2,
            'post_code' => 67890,
            'address_text' => 'شارع التحلية، حي السلامة',
            'street' => 'التحلية',
            'appartment' => 3,
            'lable' => 'المنزل',
            'is_default' => true,
        ]);
    }
    /**
     * الحصول على اسم شارع عشوائي
     */
    private function getRandomStreet(): string
    {
        $streets = [
            'النخيل',
            'الزهور',
            'الملك فهد',
            'الأمير محمد',
            'الوادي',
            'الجامعة',
            'الملك عبدالعزيز',
            'الأمير سلطان',
            'الصفا',
            'المروة'
        ];

        return $streets[array_rand($streets)];
    }

    /**
     * الحصول على اسم مدينة عشوائي
     */
    private function getRandomCity(): string
    {
        $cities = [
            'الرياض',
            'جدة',
            'الدمام',
            'مكة المكرمة',
            'المدينة المنورة',
            'الطائف',
            'تبوك',
            'القصيم',
            'حائل',
            'أبها'
        ];

        return $cities[array_rand($cities)];
    }
}
