<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the three roles defined in your migration
        DB::table('roles')->insert([
            ['role_name' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'tukang', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
