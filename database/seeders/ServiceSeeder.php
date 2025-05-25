<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Category;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Elektronik Services
        $elektronik = Category::where('name', 'Elektronik')->firstOrFail();
        $this->createServices($elektronik, [
            [
                'name' => 'Perbaikan TV',
                'description' => 'Perbaikan TV LED, LCD, dan CRT',
                'price' => 150000,
                'image_url' => 'https://images.unsplash.com/photo-1574974409771-cebec54deb00?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDd8fHJlcGFpciUyMHRlbGV2aXNpb258ZW58MHx8MHx8fDA%3D',
            ],
            [
                'name' => 'Perbaikan Kulkas',
                'description' => 'Perbaikan dan isi gas kulkas',
                'price' => 200000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Perbaikan+Kulkas',
            ],
            [
                'name' => 'Perbaikan Mesin Cuci',
                'description' => 'Perbaikan mesin cuci front load dan top load',
                'price' => 180000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Perbaikan+Mesin+Cuci',
            ],
        ]);

        // Pipa & Sanitasi Services
        $pipa = Category::where('name', 'Pipa & Sanitasi')->firstOrFail();
        $this->createServices($pipa, [
            [
                'name' => 'Perbaikan Kran',
                'description' => 'Perbaikan kran bocor dan kran rusak',
                'price' => 100000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Perbaikan+Kran',
            ],
            [
                'name' => 'Perbaikan Pipa Bocor',
                'description' => 'Penggantian dan perbaikan pipa bocor',
                'price' => 150000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Perbaikan+Pipa+Bocor',
            ],
        ]);

        // Listrik Services
        $listrik = Category::where('name', 'Listrik')->firstOrFail();
        $this->createServices($listrik, [
            [
                'name' => 'Instalasi Listrik Baru',
                'description' => 'Instalasi listrik rumah baru',
                'price' => 500000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Instalasi+Listrik+Baru',
            ],
            [
                'name' => 'Perbaikan Kabel',
                'description' => 'Perbaikan kabel listrik yang rusak',
                'price' => 120000,
                'image_url' => 'https://via.placeholder.com/300x200?text=Perbaikan+Kabel',
            ],
        ]);
    }

    private function createServices($category, $services)
    {
        foreach ($services as $service) {
            Service::create([
                'category_id' => $category->category_id,
                'title_service' => $service['name'],
                'description' => $service['description'],
                'base_price' => $service['price'],
                'image_url' => $service['image_url'],
                'provider_id' => 1, // Menggunakan provider_id default 1 untuk seeder
                'label_unit' => 'per service',
                'availbility' => true,
                'rating_avg' => null,
            ]);
        }
    }
}
