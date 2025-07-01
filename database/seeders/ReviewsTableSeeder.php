<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewsTableSeeder extends Seeder
{
    /**
     * تشغيل عملية زرع البيانات.
     */
    public function run(): void
    {
        // التأكد من وجود مستخدمين وأطباق لإضافة المراجعات
        $customers = User::where('type', 'customer')->get();
        $chefs = User::where('type', 'chef')->get();
        $dishes = Dish::all();

        // إذا لم تكن هناك بيانات كافية، لا يمكن إنشاء مراجعات
        if ($customers->isEmpty() || $chefs->isEmpty() || $dishes->isEmpty()) {
            $this->command->info('لا توجد بيانات كافية لإنشاء مراجعات. تأكد من وجود مستخدمين وأطباق.');
            return;
        }

        // حذف البيانات الموجودة في جدول المراجعات
        DB::table('reviews')->truncate();

        // إنشاء مصفوفة من التعليقات المحتملة
        $comments = [
            'طعام رائع، سأطلب مرة أخرى بالتأكيد!',
            'الطبق كان لذيذًا جدًا ومقدم بشكل جميل.',
            'جودة الطعام ممتازة والتوصيل كان سريعًا.',
            'الطعم كان جيدًا ولكن التوصيل استغرق وقتًا طويلاً.',
            'الطبق كان باردًا عند الوصول، ولكن الطعم كان جيدًا.',
            'أحببت النكهات والتوابل المستخدمة في هذا الطبق.',
            'سعر الطبق مناسب جدًا مقارنة بالجودة والكمية.',
            'الطبق كان شهيًا ولكن الكمية كانت قليلة نوعًا ما.',
            'تجربة طعام رائعة، سأوصي أصدقائي بتجربة هذا المطعم.',
            'الطبق كان متوسطًا، لم يكن سيئًا ولكن ليس استثنائيًا.',
        ];

        // إنشاء 50 مراجعة عشوائية
        $reviewsData = [];
        for ($i = 0; $i < 50; $i++) {
            // اختيار عشوائي للمستخدم والطاهي والطبق
            $customer = $customers->random();
            $dish = $dishes->random();
            $chef = $chefs->random(); // يمكن استبداله بالطاهي المرتبط بالطبق إذا كان ذلك متاحًا

            // إنشاء تقييم عشوائي بين 1 و 5
            $rating = rand(1, 5);

            // اختيار تعليق عشوائي
            $comment = $comments[array_rand($comments)];

            // إضافة المراجعة إلى المصفوفة
            $reviewsData[] = [
                'customer_id' => $customer->id,
                'chef_id' => $chef->id,
                'dish_id' => $dish->id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'), // تاريخ عشوائي خلال الشهر الماضي
            ];
        }

        // إدخال البيانات في قاعدة البيانات
        DB::table('reviews')->insert($reviewsData);

        $this->command->info('تم إنشاء 50 مراجعة بنجاح!');
    }
}
