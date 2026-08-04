<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\ProductItem;
use App\Models\DeliveryZone;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Spatie Roles සෑදීම (පද්ධතියට අත්‍යවශ්‍ය Roles සියල්ල මෙහිදී සැකසේ)
        $roles = ['admin', 'kitchen', 'delivery', 'customer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Run Role Seeder if exists
        if (class_exists(RoleSeeder::class)) {
            $this->call([
                RoleSeeder::class,
            ]);
        }

        // 3. Create Default System Users for Testing & Assign Spatie Roles

        // --- Admin User ---
        $admin = User::firstOrCreate(
            ['email' => 'admin@bci.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole('admin');

        // --- Kitchen User ---
        $kitchen = User::firstOrCreate(
            ['email' => 'kitchen@bci.com'],
            [
                'name' => 'Head Chef',
                'password' => Hash::make('password'),
                'role' => 'kitchen',
            ]
        );
        $kitchen->assignRole('kitchen');

        // --- Delivery User ---
        $delivery = User::firstOrCreate(
            ['email' => 'delivery@bci.com'],
            [
                'name' => 'Delivery Driver',
                'password' => Hash::make('password'),
                'role' => 'delivery',
            ]
        );
        $delivery->assignRole('delivery');

        // --- Customer User ---
        $customer = User::firstOrCreate(
            ['email' => 'customer@bci.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
        $customer->assignRole('customer');

        // 4. Create Sample Delivery Zones
        try {
            DeliveryZone::firstOrCreate(['name' => 'Zone A - Downtown']);
            DeliveryZone::firstOrCreate(['name' => 'Zone B - Suburbs']);
        } catch (\Exception $e) {
            // Ignore if zone creation logic differs
        }

        // 5. Run Product Seeder
        if (class_exists(ProductSeeder::class)) {
            $this->call([
                ProductSeeder::class,
            ]);
        }
    }
}