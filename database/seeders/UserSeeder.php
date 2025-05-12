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
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@tatafix.com',
                'password' => Hash::make('admin123'),
                'phone' => '081234567899',
                'address' => 'Kantor Pusat',
                'role' => 'admin',
            ],
            [
                'name' => 'Tukang Satu',
                'email' => 'tukang@tatafix.com',
                'password' => Hash::make('tukang123'),
                'phone' => '081234567800',
                'address' => 'Bandung',
                'role' => 'tukang',
            ],
            [
                'name' => 'Customer Satu',
                'email' => 'customer@tatafix.com',
                'password' => Hash::make('customer123'),
                'phone' => '081234567801',
                'address' => 'Jakarta',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'phone' => $userData['phone'],
                    'address' => $userData['address'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);
        }
    }
}
