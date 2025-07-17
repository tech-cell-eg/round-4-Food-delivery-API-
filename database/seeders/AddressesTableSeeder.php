<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\Customer;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first few customers from the database
        $customers = Customer::take(3)->get();

        if ($customers->count() < 2) {
            $this->command->error('Not enough customers found. Please run CustomerSeeder first.');
            return;
        }

        // إنشاء عناوين للعملاء
        Address::create([
            'customer_id' => $customers[0]->id,
            'lat' => 24.7136,
            'lon' => 46.6753,
            'name' => 'الملك فهد',
            'is_default' => true,
        ]);

        Address::create([
            'customer_id' => $customers[0]->id,
            'lat' => 24.7243,
            'lon' => 46.6814,
            'name' => 'العليا',
            'is_default' => false,
        ]);

        Address::create([
            'customer_id' => $customers[1]->id,
            'lat' => 21.4858,
            'lon' => 39.1925,
            'name' => 'التحلية',

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
