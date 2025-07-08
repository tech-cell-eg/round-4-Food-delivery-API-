<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
                'name' => 'Tiba Grill',
                'email' => 'mohamedahmeddev333@gmail.com',
                'password' => Hash::make('mohamedahmeddev333@gmail.com'),
                'phone' => '+201020129655',
                'profile_image' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=300',
                'bio' => 'Serving authentic Egyptian grilled dishes with a modern twist in a cozy setting.',
                'email_verified_at' => now(),
                'type' => "admin",
        ]);

        Admin::create([
            "id" => $user->id,
        ]);
    }
}
