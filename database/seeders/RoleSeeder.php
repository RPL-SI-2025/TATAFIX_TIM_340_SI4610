<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin'],
            ['role_name' => 'customer'],
            ['role_name' => 'tukang'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
