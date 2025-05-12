<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'tukang', 'guard_name' => 'web'],
            ['name' => 'customer', 'guard_name' => 'web'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate($roleData);
        }
    }
}
