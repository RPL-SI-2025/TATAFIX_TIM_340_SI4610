<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Perbaikan dan perawatan alat elektronik rumah tangga',
            ],
            [
                'name' => 'Kendaraan',
                'description' => 'Servis dan perawatan kendaraan bermotor',
            ],
            [
                'name' => 'Rumah Tangga',
                'description' => 'Layanan perbaikan rumah dan kebersihan',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
