<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TukangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tukangs')->insert([
            [
                'name' => 'Tukang A',
                'email' => 'tukangA@example.com',
                'kata_sandi' => Hash::make('passwordA'),
            ],
            [
                'name' => 'Tukang B',
                'email' => 'tukangB@example.com',
                'kata_sandi' => Hash::make('passwordB'),
            ],
            [
                'name' => 'Tukang C',
                'email' => 'tukangC@example.com',
                'kata_sandi' => Hash::make('passwordC'),
            ],
        ]);
    }
}
