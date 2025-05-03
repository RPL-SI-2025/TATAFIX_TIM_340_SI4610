<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Customer Satu',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // asumsikan 2 adalah role customer
            'phone' => '081234567890',
        ]);
        User::create([
            'name' => 'Customer Dua',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'phone' => '081234567891',
        ]);
    }
}
