<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        // Use Laravel's notification system to send a welcome notification to each user
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\WelcomeNotification());
        }
    }
}
