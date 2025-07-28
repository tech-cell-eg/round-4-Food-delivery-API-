<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPermissions();

        $managerRole = $this->createManagerRole();

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

        $admin = Admin::create([
            'id' => $user->id,
        ]);

        $admin->assignRole($managerRole); // هتشتغل صح والـ model_id هيكون مظبوط

    }


    private function createPermissions(): void
    {
        $permissions = [
            "manage_admins",
            "manage_roles",
            "manage_permissions",
            "manage_orders",
            "manage_payments",
            "manage_users",
            "manage_categories",
            "manage_ingredients",
            "manage_dishes",
            "manage_chefs",
            "manage_coupons",
            "manage_customers",
            "view_payments"
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => "admin",
            ]);
        }
    }


    private function createManagerRole(): Role
    {
        $managerRole = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => "admin",

        ]);

        $allPermissions = Permission::all();

        $managerRole->syncPermissions($allPermissions);

        return $managerRole;
    }
}
