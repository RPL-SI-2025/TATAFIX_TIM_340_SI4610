<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tukang']);
        Role::create(['name' => 'customer']);
=======
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'tukang', 'guard_name' => 'web']);
        Role::create(['name' => 'customer', 'guard_name' => 'web']);
>>>>>>> 4928bfea38e58cab60b182f1b4576baa7dc4643e
    }
}
