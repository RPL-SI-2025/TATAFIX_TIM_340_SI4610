<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Layanan perbaikan perangkat elektronik dan peralatan rumah tangga',
            ],
            [
                'name' => 'Pipa & Sanitasi',
                'description' => 'Layanan perbaikan pipa, kran, dan sistem sanitasi',
            ],
            [
                'name' => 'Listrik',
                'description' => 'Layanan perbaikan dan instalasi sistem listrik',
            ],
            [
                'name' => 'AC & Pendingin',
                'description' => 'Layanan perbaikan dan perawatan AC serta pendingin ruangan',
            ],
            [
                'name' => 'Pintu & Jendela',
                'description' => 'Layanan perbaikan pintu, jendela, dan kunci',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
