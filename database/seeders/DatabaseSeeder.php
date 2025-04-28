<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        // Buat user dummy sebelum service
        User::factory()->create([
            'user_id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 1, // admin
        ]);

        $this->call([
            ServiceSeeder::class,
        ]);
    }
}
