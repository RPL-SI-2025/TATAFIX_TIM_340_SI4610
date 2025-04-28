<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Tukang A',
                'email' => 'tukangA@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jalan Tukang No.1',
                'role_id' => 3, // role tukang
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tukang B',
                'email' => 'tukangB@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jalan Tukang No.2',
                'role_id' => 3,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tukang C',
                'email' => 'tukangC@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'address' => 'Jalan Tukang No.3',
                'role_id' => 3,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
