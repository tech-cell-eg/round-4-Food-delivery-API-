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
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'ban-users',
            'unban-users',

            // Chef Management
            'view-chefs',
            'create-chefs',
            'edit-chefs',
            'delete-chefs',
            'approve-chefs',
            'reject-chefs',
            'suspend-chefs',

            // Dish Management
            'view-dishes',
            'create-dishes',
            'edit-dishes',
            'delete-dishes',
            'approve-dishes',
            'reject-dishes',
            'feature-dishes',

            // Category Management
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            'reorder-categories',

            // Order Management
            'view-orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'cancel-orders',
            'refund-orders',
            'assign-delivery',
            'track-orders',

            // Payment Management
            'view-payments',
            'process-payments',
            'refund-payments',
            'view-payment-methods',
            'manage-payment-methods',

            // Review Management
            'view-reviews',
            'edit-reviews',
            'delete-reviews',
            'approve-reviews',
            'reject-reviews',

            // Coupon Management
            'view-coupons',
            'create-coupons',
            'edit-coupons',
            'delete-coupons',
            'activate-coupons',
            'deactivate-coupons',

            // Area/Location Management
            'view-areas',
            'create-areas',
            'edit-areas',
            'delete-areas',
            'manage-delivery-zones',

            // Notification Management
            'view-notifications',
            'send-notifications',
            'edit-notifications',
            'delete-notifications',
            'send-bulk-notifications',

            // Role & Permission Management
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'assign-roles',

            // Admin Management
            'view-admins',
            'create-admins',
            'edit-admins',
            'delete-admins',
            'manage-admin-roles',

            // Report Management
            'view-reports',
            'generate-reports',
            'export-reports',
            'view-analytics',
            'view-sales-reports',
            'view-chef-reports',
            'view-user-reports',

            // Settings Management
            'view-settings',
            'edit-settings',
            'manage-app-settings',
            'manage-payment-settings',
            'manage-notification-settings',
            'manage-delivery-settings',

            // Content Management
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'publish-content',

            // Support Management
            'view-support-tickets',
            'create-support-tickets',
            'edit-support-tickets',
            'close-support-tickets',
            'assign-support-tickets',

            // System Management
            'view-logs',
            'clear-logs',
            'backup-database',
            'restore-database',
            'manage-cache',
            'manage-queue',

            // Dashboard Access
            'access-dashboard',
            'view-dashboard-stats',
            'view-dashboard-charts',
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
