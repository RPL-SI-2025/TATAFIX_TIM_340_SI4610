<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
<<<<<<< HEAD
=======
            CategorySeeder::class,
            ServiceSeeder::class,
>>>>>>> 4928bfea38e58cab60b182f1b4576baa7dc4643e
        ]);
    }
    
    
}
<<<<<<< HEAD
=======




>>>>>>> 4928bfea38e58cab60b182f1b4576baa7dc4643e
