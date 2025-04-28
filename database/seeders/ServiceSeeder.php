<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $categories = Category::all()->keyBy('name');

        $services = [
            [
                'provider_id' => $user ? $user->user_id : 1,
                'title_service' => 'Service AC',
                'description' => 'Perbaikan dan perawatan AC rumah',
                'category_id' => $categories['Elektronik']->category_id ?? 1,
                'base_price' => 150000,
                'label_unit' => 'unit',
                'availbility' => true,
                'rating_avg' => 4.5,
            ],
            [
                'provider_id' => $user ? $user->user_id : 1,
                'title_service' => 'Service Motor',
                'description' => 'Servis berkala motor matic & bebek',
                'category_id' => $categories['Kendaraan']->category_id ?? 2,
                'base_price' => 80000,
                'label_unit' => 'motor',
                'availbility' => true,
                'rating_avg' => 4.7,
            ],
            [
                'provider_id' => $user ? $user->user_id : 1,
                'title_service' => 'Cleaning Service',
                'description' => 'Jasa kebersihan rumah dan kantor',
                'category_id' => $categories['Rumah Tangga']->category_id ?? 3,
                'base_price' => 100000,
                'label_unit' => 'jam',
                'availbility' => true,
                'rating_avg' => 4.6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
