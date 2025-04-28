<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TukangSeeder extends Seeder
{
    public function run()
    {
        DB::table('tukang')->insert([
            [
                'foto' => 'default.jpg',
                'nama' => 'Budi Santoso',
                'domisili' => 'Bandung',
                'no_hp' => '08123456789',
                'email' => 'budi@example.com',
            ],
            [
                'foto' => 'default2.jpg',
                'nama' => 'Siti Aminah',
                'domisili' => 'Jakarta',
                'no_hp' => '08234567890',
                'email' => 'siti@example.com',
            ]
        ]);
    }
}
