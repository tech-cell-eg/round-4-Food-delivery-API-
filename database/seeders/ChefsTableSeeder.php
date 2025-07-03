<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chef;

class ChefsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء بيانات الطهاة المرتبطة بالمستخدمين
        Chef::create([
            'id' => 3, // مرتبط بالمستخدم الذي دوره chef
            'speciality' => 'المطبخ الشرقي',
            'experience_years' => 10,
            'bio' => 'شيف متخصص في المطبخ الشرقي والأطباق التقليدية',
            'is_available' => true,
            'national_id' => 'CH123456',
            'balance' => 1000.00,
            'description' => 'شيف متخصص في المطبخ الشرقي مع خبرة 10 سنوات',
            'location' => 'الرياض',
            'image' => 'chefs/chef1.jpg',
            'is_verified' => true,
        ]);

        Chef::create([
            'id' => 4, // مرتبط بالمستخدم الآخر الذي دوره chef
            'speciality' => 'المطبخ الفرنسي',
            'experience_years' => 14,
            'bio' => 'شيف متخصص في المطبخ الفرنسي',
            'is_available' => true,
            'national_id' => 'CH789012',
            'balance' => 1500.00,
            'description' => 'شيف متخصص في المطبخ الفرنسي مع خبرة 14 سنة',
            'location' => 'جدة',
            'image' => 'chefs/chef2.jpg',
            'is_verified' => true,
        ]);
    }
    private function getRandomSpeciality(): string
    {
        $specialities = [
            'المأكولات البحرية',
            'المعجنات',
            'اللحوم',
            'الحلويات',
            'المطبخ الإيطالي',
            'المطبخ الشرقي',
            'المطبخ الهندي',
            'المطبخ المكسيكي',
            'المطبخ الصيني',
            'المطبخ الياباني'
        ];

        return $specialities[array_rand($specialities)];
    }

    /**
     * الحصول على مطبخ عشوائي
     */
    private function getRandomCuisine(): string
    {
        $cuisines = [
            'الإيطالي',
            'الشرقي',
            'الهندي',
            'المكسيكي',
            'الصيني',
            'الياباني',
            'الفرنسي',
            'التركي',
            'اللبناني',
            'المصري'
        ];

        return $cuisines[array_rand($cuisines)];
    }
}
