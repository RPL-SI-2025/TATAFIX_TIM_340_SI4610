<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceParameter;
use App\Models\Service;

class ServiceParameterSeeder extends Seeder
{
    public function run(): void
    {
        // Parameter untuk jasa elektronik
        $layananElektronik = Service::where('title_service', 'like', '%elektronik%')->first();
        if ($layananElektronik) {
            ServiceParameter::create([
                'service_id' => $layananElektronik->service_id,
                'name' => 'Luas Area',
                'unit' => 'm²',
                'price_per_unit' => 10000, // Harga per m²
            ]);
        }

        // Parameter untuk jasa perbaikan TV
        $layananTV = Service::where('title_service', 'like', '%TV%')->first();
        if ($layananTV) {
            ServiceParameter::create([
                'service_id' => $layananTV->service_id,
                'name' => 'Jenis Perbaikan',
                'unit' => 'jenis',
                'price_per_unit' => 100000, // Harga per jenis perbaikan
            ]);
        }

        // Parameter untuk jasa perbaikan AC
        $layananAC = Service::where('title_service', 'like', '%AC%')->first();
        if ($layananAC) {
            ServiceParameter::create([
                'service_id' => $layananAC->service_id,
                'name' => 'Jenis Perbaikan',
                'unit' => 'jenis',
                'price_per_unit' => 150000, // Harga per jenis perbaikan
            ]);
        }

        // Parameter untuk jasa perbaikan komputer
        $layananKomputer = Service::where('title_service', 'like', '%komputer%')->first();
        if ($layananKomputer) {
            ServiceParameter::create([
                'service_id' => $layananKomputer->service_id,
                'name' => 'Jenis Perbaikan',
                'unit' => 'jenis',
                'price_per_unit' => 120000, // Harga per jenis perbaikan
            ]);
        }

        // Parameter untuk jasa perbaikan kulkas
        $layananKulkas = Service::where('title_service', 'like', '%kulkas%')->first();
        if ($layananKulkas) {
            ServiceParameter::create([
                'service_id' => $layananKulkas->service_id,
                'name' => 'Jenis Perbaikan',
                'unit' => 'jenis',
                'price_per_unit' => 130000, // Harga per jenis perbaikan
            ]);
        }
    }
}