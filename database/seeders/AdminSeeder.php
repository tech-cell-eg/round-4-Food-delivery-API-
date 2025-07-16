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

        $managerRole = $this->createSuperAdminRole();

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
            // Admin Management
            'manage_admins',
            
            // Role and Permission Management
            'manage_roles',
            'manage_permissions',
            
            // User Management
            'manage_customers',
            'manage_chefs',
            
            // Content Management
            'manage_categories',
            'manage_ingredients',
            'manage_dishes',
            'manage_coupons',
            
            // Order Management
            'manage_orders',
            
            // Financial
            'view_payments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }
    }


    private function createSuperAdminRole(): Role
    {
        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'admin',
        ]);

        $allPermissions = Permission::where('guard_name', 'admin')->get();

        $managerRole->syncPermissions($allPermissions);

        return $managerRole;
    }
}
