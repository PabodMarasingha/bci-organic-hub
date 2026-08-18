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
        // 1. Driver User සෑදීම
        $driverUser = User::firstOrCreate(
            ['email' => 'driver1@example.com'],
            [
                'name' => 'Saman Perera (Rider)',
                'password' => bcrypt('password'),
                'phone' => '0771234567',
            ]
        );

        // 2. Delivery Zone එකක් සෑදීම
        $zoneId = DB::table('delivery_zones')->insertGetId([
            'name' => 'Colombo Central Zone',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Driver Profile සෑදීම
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

        // 4. Customer User සෑදීම
        $customer = User::firstOrCreate(
            ['email' => 'customer1@example.com'],
            [
                'name' => 'Nimal Jayasinghe',
                'password' => bcrypt('password'),
                'phone' => '0719876543',
            ]
        );

        // 5. Test Order එකක් සෑදීම (orders table)
        $order = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 2850.00,
            'status' => 'dispatched',
            'special_instructions' => 'Call before arrival. Leave package at front door.',
            'dropoff_address' => 'No. 12, Main Street, Colombo 03',
        ]);

        // 6. customer_orders Table එකට දත්ත ඇතුළත් කිරීම
        $customerOrderId = DB::table('customer_orders')->insertGetId([
            'user_id' => $customer->id,
            'delivery_zone_id' => $zoneId,
            'total_amount' => 2850.00,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Delivery Active Task එක සෑදීම
        Delivery::create([
            'order_id' => $order->id,
            'customer_order_id' => $customerOrderId,
            'driver_id' => $driverUser->id,
            'delivery_status' => 'unassigned',
            'dropoff_address' => $order->dropoff_address,
        ]);
    }
}