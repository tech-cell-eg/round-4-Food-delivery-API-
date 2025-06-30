<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\DishSize;
use App\Models\Chef;
use App\Models\Category;

class DishesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefs = Chef::all();
        $pizza = Category::where('name', 'بيتزا')->first();
        $burger = Category::where('name', 'برجر')->first();
        $salad = Category::where('name', 'سلطة')->first();
        $kebab = Category::where('name', 'مشويات')->first();
        $seafood = Category::where('name', 'مأكولات بحرية')->first();
        $dissert = Category::where('name', 'حلويات')->first();
        $boverage = Category::where('name', 'مشروبات')->first();
        $fastFood = Category::where('name', 'وجبات سريعة')->first();

        // قائمة الأطباق
        $dishes = [
            [
                'name' => 'بيتزا مارجريتا',
                'description' => 'بيتزا كلاسيكية مع صلصة الطماطم وجبنة الموزاريلا وأوراق الريحان',
                'image' => 'margherita_pizza.jpg',
                'category_id' => $pizza->id,
            ],
            [
                'name' => 'بيتزا بيبروني',
                'description' => 'بيتزا مع صلصة الطماطم وجبنة الموزاريلا وشرائح البيبروني',
                'image' => 'pepperoni_pizza.jpg',
                'category_id' => $pizza->id,
            ],
            [
                'name' => 'برجر لحم',
                'description' => 'برجر لحم بقري مشوي مع جبنة الشيدر والخس والطماطم والبصل',
                'image' => 'beef_burger.jpg',
                'category_id' => $burger->id,
            ],
            [
                'name' => 'برجر دجاج',
                'description' => 'برجر دجاج مقرمش مع صلصة المايونيز والخس والطماطم',
                'image' => 'chicken_burger.jpg',
                'category_id' => $burger->id,
            ],
            [
                'name' => 'سلطة سيزر',
                'description' => 'سلطة طازجة مع خس روماني وقطع الدجاج المشوي وجبنة البارميزان وصلصة سيزر',
                'image' => 'caesar_salad.jpg',
                'category_id' => $salad->id,
            ],
            [
                'name' => 'سلطة يونانية',
                'description' => 'سلطة مع خيار وطماطم وفلفل وبصل وزيتون وجبنة فيتا',
                'image' => 'greek_salad.jpg',
                'category_id' => $salad->id,
            ],
            [
                'name' => 'كباب لحم',
                'description' => 'كباب لحم مشوي مع بهارات شرقية وأرز بسمتي',
                'image' => 'meat_kebab.jpg',
                'category_id' => $kebab->id,
            ],
            [
                'name' => 'شيش طاووق',
                'description' => 'قطع دجاج متبلة ومشوية مع صلصة الثوم والبطاطا المقلية',
                'image' => 'shish_tawook.jpg',
                'category_id' => $kebab->id,
            ],
            [
                'name' => 'سمك مشوي',
                'description' => 'سمك طازج مشوي مع الليمون والأعشاب والخضروات',
                'image' => 'grilled_fish.jpg',
                'category_id' => $seafood->id,
            ],
            [
                'name' => 'روبيان مقلي',
                'description' => 'روبيان مقلي مقرمش مع صلصة الثوم والليمون',
                'image' => 'fried_shrimp.jpg',
                'category_id' => $dissert->id,
            ],
            [
                'name' => 'كنافة',
                'description' => 'حلوى شرقية مصنوعة من العجين الرفيع والجبن والقطر',
                'image' => 'kunafa.jpg',
                'category_id' => $dissert->id,
            ],
            [
                'name' => 'تشيز كيك',
                'description' => 'كعكة الجبن الكريمية مع صلصة التوت',
                'image' => 'cheesecake.jpg',
                'category_id' => $dissert->id,
            ],
            [
                'name' => 'عصير برتقال',
                'description' => 'عصير برتقال طازج',
                'image' => 'orange_juice.jpg',
                'category_id' => $boverage->id,
            ],
            [
                'name' => 'عصير مانجو',
                'description' => 'عصير مانجو طبيعي',
                'image' => 'mango_juice.jpg',
                'category_id' => $boverage->id,
            ],
            [
                'name' => 'شاورما دجاج',
                'description' => 'شاورما دجاج مع صلصة الثوم والخضروات',
                'image' => 'chicken_shawarma.jpg',
                'category_id' => $fastFood->id,
            ],
        ];

        foreach ($dishes as $dishData) {
            // البحث عن الفئة المناسبة


            // اختيار شيف عشوائي
            $chef = $chefs->random();

            // إنشاء الطبق
            $dish = Dish::create([
                'chef_id' => $chef->id,
                'name' => $dishData['name'],
                'description' => $dishData['description'],
                'image' => $dishData['image'],
                'is_available' => true,
                'total_rate' => rand(100, 5000),
                'avg_rate' => rand(3, 5),
                'category_id' => $dishData['category_id'],
            ]);

            // إنشاء أحجام للطبق مع أسعار مختلفة
            DishSize::create([
                'dish_id' => $dish->id,
                'size' => 'small',
                'price' => rand(15, 25),
            ]);

            DishSize::create([
                'dish_id' => $dish->id,
                'size' => 'medium',
                'price' => rand(25, 40),
            ]);

            DishSize::create([
                'dish_id' => $dish->id,
                'size' => 'large',
                'price' => rand(40, 60),
            ]);
        }
    }
}
