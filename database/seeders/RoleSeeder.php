<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['customer', 'kitchen', 'delivery', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Give your existing test user(s) the customer role by default
        User::whereDoesntHave('roles')->get()->each(function ($user) {
            $user->assignRole('customer');
        });

        // Create dedicated staff test accounts
        $kitchen = User::firstOrCreate(
            ['email' => 'kitchen@bci.test'],
            ['name' => 'Kitchen Staff', 'password' => bcrypt('password')]
        );
        $kitchen->syncRoles(['kitchen']);

        $delivery = User::firstOrCreate(
            ['email' => 'delivery@bci.test'],
            ['name' => 'Delivery Staff', 'password' => bcrypt('password')]
        );
        $delivery->syncRoles(['delivery']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@bci.test'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $admin->syncRoles(['admin']);
    }
}