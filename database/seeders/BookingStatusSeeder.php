<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingStatus;

class BookingStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'status_code' => 'PENDING',
                'display_name' => 'Menunggu Konfirmasi',
            ],
            [
                'status_code' => 'CONFIRMED',
                'display_name' => 'Dikonfirmasi',
            ],
            [
                'status_code' => 'ON_PROCESS',
                'display_name' => 'Sedang Diproses',
            ],
            [
                'status_code' => 'COMPLETED',
                'display_name' => 'Selesai',
            ],
            [
                'status_code' => 'CANCELLED',
                'display_name' => 'Dibatalkan',
            ],
        ];

        foreach ($statuses as $status) {
            BookingStatus::create($status);
        }
    }
}
