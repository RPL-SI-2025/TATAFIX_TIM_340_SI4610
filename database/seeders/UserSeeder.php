<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@tatafix.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567899',
            'address' => 'Kantor Pusat',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin->assignRole('admin');

        $tukang = User::create([
            'name' => 'Tukang Satu',
            'email' => 'tukang@tatafix.com',
            'password' => Hash::make('tukang123'),
            'phone' => '081234567800',
            'address' => 'Bandung',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $tukang->assignRole('tukang');

        $customer = User::create([
            'name' => 'Customer Satu',
            'email' => 'customer@tatafix.com',
            'password' => Hash::make('customer123'),
            'phone' => '081234567801',
            'address' => 'Jakarta',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $customer->assignRole('customer');
    }
}
