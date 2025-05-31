<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\BookingStatus;

class CustomerBookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $services = Service::all();
        $statuses = BookingStatus::all();
        
        if ($users->isEmpty() || $services->isEmpty() || $statuses->isEmpty()) {
            $this->command->error('Cannot create bookings: missing users, services, or statuses');
            return;
        }
        $services = Service::whereNotNull('service_id')->get();
        
        if ($services->isEmpty()) {
            $this->command->error('No valid services found. Please check ServiceSeeder.');
            return;
        }
        
        $serviceIds = $services->pluck('service_id')->toArray();

        $bookings = [
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Ahmad Fauzi',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-28',
                'status_id' => $statuses->where('status_code', 'WAITING_DP')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Budi Santoso',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-28',
                'status_id' => $statuses->where('status_code', 'PENDING')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Citra Dewi',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-28',
                'status_id' => $statuses->where('status_code', 'CONFIRMED')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Deni Wijaya',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-28',
                'status_id' => $statuses->where('status_code', 'ON_PROCESS')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Eka Putri',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-28',
                'status_id' => $statuses->where('status_code', 'COMPLETED')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Fajar Nugraha',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-29',
                'status_id' => $statuses->where('status_code', 'PENDING')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Gita Lestari',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-29',
                'status_id' => $statuses->where('status_code', 'CONFIRMED')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Hadi Susanto',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-30',
                'status_id' => $statuses->where('status_code', 'ON_PROCESS')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Indah Permata',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-30',
                'status_id' => $statuses->where('status_code', 'COMPLETED')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Joko Prabowo',
                'service_name' => $services->random()->title_service, // Pastikan ini sudah benar
                'tanggal_booking' => '2025-05-31',
                'status_id' => $statuses->where('status_code', 'CANCELLED')->first()->id ?? $statuses->first()->id,
            ],
        ];
        
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}