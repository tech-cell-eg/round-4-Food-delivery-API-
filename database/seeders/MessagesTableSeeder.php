<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MessagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            [
                'conversation_id' => 1,
                'sender_type' => 'customer',
                'sender_id' => 1,
                'message' => 'مرحباً، أريد الاستفسار عن حالة طلبي.',
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes(10),
                'updated_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'conversation_id' => 1,
                'sender_type' => 'chef',
                'sender_id' => 2,
                'message' => 'أهلاً بك! طلبك قيد التحضير وسيتم توصيله قريباً.',
                'read_at' => Carbon::now()->subMinutes(5),
                'created_at' => Carbon::now()->subMinutes(8),
                'updated_at' => Carbon::now()->subMinutes(8),
            ],
            [
                'conversation_id' => 2,
                'sender_type' => 'customer',
                'sender_id' => 3,
                'message' => 'هل يمكنني تعديل الطلب؟',
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes(7),
                'updated_at' => Carbon::now()->subMinutes(7),
            ],
        ]);
    }
}
