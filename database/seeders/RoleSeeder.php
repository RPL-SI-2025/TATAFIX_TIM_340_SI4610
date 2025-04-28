<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_id' => 1,
                'role_name' => 'customer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 2,
                'role_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'role_name' => 'tukang',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
