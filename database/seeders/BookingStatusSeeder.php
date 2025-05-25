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
                'status_code' => 'WAITING_DP',
                'display_name' => 'Menunggu Pembayaran DP',
                'color_code' => 'yellow',
                'requires_action' => true,
                'next_status' => 'VALIDATING_DP'
            ],
            // Tambahkan status lain yang Anda butuhkan
            [
                'status_code' => 'PENDING',
                'display_name' => 'Menunggu Konfirmasi',
                'color_code' => 'yellow',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'CONFIRMED',
                'display_name' => 'Dikonfirmasi',
                'color_code' => 'blue',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'ON_PROCESS',
                'display_name' => 'Sedang Diproses',
                'color_code' => 'blue',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'COMPLETED',
                'display_name' => 'Selesai',
                'color_code' => 'green',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'CANCELLED',
                'display_name' => 'Dibatalkan',
                'color_code' => 'red',
                'requires_action' => false,
                'next_status' => null
            ],
        ];

        foreach ($statuses as $status) {
            BookingStatus::firstOrCreate(
                ['status_code' => $status['status_code']],
                $status
            );
        }
    }
}