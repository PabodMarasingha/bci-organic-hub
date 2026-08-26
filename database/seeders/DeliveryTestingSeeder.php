<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DeliveryProfile;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;

class DeliveryTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Driver User
        $driverUser = User::firstOrCreate(
            ['email' => 'driver1@example.com'],
            [
                'name' => 'Saman Perera (Rider)',
                'password' => bcrypt('password'),
                'phone' => '0771234567',
            ]
        );

        // 2. Delivery Zone — only create if it doesn't already exist
        $zoneId = DB::table('delivery_zones')->where('name', 'Colombo Central Zone')->value('id');
        if (!$zoneId) {
            $zoneId = DB::table('delivery_zones')->insertGetId([
                'name' => 'Colombo Central Zone',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Driver Profile
        DeliveryProfile::updateOrCreate(
            ['user_id' => $driverUser->id],
            [
                'delivery_zone_id' => $zoneId,
                'vehicle_type' => 'motorbike',
                'vehicle_number' => 'WP BCD-4589',
                'is_available' => true,
                'current_status' => 'available'
            ]
        );

        // 4. Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer1@example.com'],
            [
                'name' => 'Nimal Jayasinghe',
                'password' => bcrypt('password'),
                'phone' => '0719876543',
            ]
        );

        // 5. Test Order — keyed on customer + dropoff address so re-seeding won't duplicate it
        $order = Order::firstOrCreate(
            [
                'user_id' => $customer->id,
                'dropoff_address' => 'No. 12, Main Street, Colombo 03',
            ],
            [
                'total_amount' => 2850.00,
                'status' => 'dispatched',
                'special_instructions' => 'Call before arrival. Leave package at front door.',
            ]
        );

        // 6. customer_orders — only insert if this customer/zone combo doesn't already exist
        $customerOrderId = DB::table('customer_orders')
            ->where('user_id', $customer->id)
            ->where('delivery_zone_id', $zoneId)
            ->value('id');

        if (!$customerOrderId) {
            $customerOrderId = DB::table('customer_orders')->insertGetId([
                'user_id' => $customer->id,
                'delivery_zone_id' => $zoneId,
                'total_amount' => 2850.00,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 7. Delivery — keyed on the order, so re-seeding updates rather than duplicates
        Delivery::updateOrCreate(
            ['order_id' => $order->id],
            [
                'customer_order_id' => $customerOrderId,
                'driver_id' => $driverUser->id,
                'delivery_status' => 'unassigned',
                'dropoff_address' => $order->dropoff_address,
            ]
        );
    }
}