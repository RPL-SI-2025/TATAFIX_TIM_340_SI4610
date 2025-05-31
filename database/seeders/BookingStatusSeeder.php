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
                'status_code' => 'pending',
                'display_name' => 'Menunggu Pembayaran DP',
                'color_code' => 'yellow',
                'requires_action' => true,
                'next_status' => 'waiting_validation_dp'
            ],
            [
                'status_code' => 'waiting_validation_dp',
                'display_name' => 'Menunggu Validasi DP',
                'color_code' => 'blue',
                'requires_action' => true,
                'next_status' => 'dp_validated'
            ],
            [
                'status_code' => 'dp_validated',
                'display_name' => 'DP Divalidasi Admin',
                'color_code' => 'green',
                'requires_action' => false,
                'next_status' => 'in_progress'
            ],
            [
                'status_code' => 'in_progress',
                'display_name' => 'Sedang Dikerjakan',
                'color_code' => 'blue',
                'requires_action' => false,
                'next_status' => 'done'
            ],
            [
                'status_code' => 'done',
                'display_name' => 'Selesai',
                'color_code' => 'green',
                'requires_action' => false,
                'next_status' => 'waiting_pelunasan'
            ],
            [
                'status_code' => 'waiting_pelunasan',
                'display_name' => 'Menunggu Pelunasan',
                'color_code' => 'yellow',
                'requires_action' => true,
                'next_status' => 'waiting_validation_pelunasan'
            ],
            [
                'status_code' => 'waiting_validation_pelunasan',
                'display_name' => 'Validasi Pelunasan',
                'color_code' => 'blue',
                'requires_action' => true,
                'next_status' => 'completed'
            ],
            [
                'status_code' => 'completed',
                'display_name' => 'Selesai Final',
                'color_code' => 'green',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'rejected',
                'display_name' => 'Ditolak',
                'color_code' => 'red',
                'requires_action' => false,
                'next_status' => null
            ],
            [
                'status_code' => 'canceled',
                'display_name' => 'Dibatalkan Customer',
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