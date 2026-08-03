<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\ProductItem;
use App\Models\DeliveryZone;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run Role Seeder if exists
        if (class_exists(RoleSeeder::class)) {
            $this->call([
                RoleSeeder::class,
            ]);
        }

        // 2. Create Default System Users for Testing
        User::firstOrCreate(
            ['email' => 'admin@bci.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kitchen@bci.com'],
            [
                'name' => 'Head Chef',
                'password' => Hash::make('password'),
                'role' => 'kitchen',
            ]
        );

        User::firstOrCreate(
            ['email' => 'delivery@bci.com'],
            [
                'name' => 'Delivery Driver',
                'password' => Hash::make('password'),
                'role' => 'delivery',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@bci.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 3. Create Sample Delivery Zones (Safe FirstOrCreate)
        try {
            DeliveryZone::firstOrCreate(['name' => 'Zone A - Downtown']);
            DeliveryZone::firstOrCreate(['name' => 'Zone B - Suburbs']);
        } catch (\Exception $e) {
            // Ignore if zone creation logic differs
        }

        // 4. Run Product Seeder
        if (class_exists(ProductSeeder::class)) {
            $this->call([
                ProductSeeder::class,
            ]);
        }
    }
}