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
        // Data sample untuk booking
        $bookings = [
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Ahmad Fauzi',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'no_handphone' => '081234567890',
                'tanggal_booking' => now()->addDays(3),
                'waktu_booking' => '09:00:00',
                'catatan_perbaikan' => 'Perbaikan atap bocor',
                'status_id' => $statuses->where('status_code', 'WAITING_DP')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Budi Santoso',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                'no_handphone' => '082345678901',
                'tanggal_booking' => now()->addDays(5),
                'waktu_booking' => '13:00:00',
                'catatan_perbaikan' => 'Perbaikan keran air yang rusak',
                'status_id' => $statuses->where('status_code', 'PENDING')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Citra Dewi',
                'alamat' => 'Jl. Gatot Subroto No. 67, Jakarta',
                'no_handphone' => '083456789012',
                'tanggal_booking' => now()->addDays(7),
                'waktu_booking' => '10:00:00',
                'catatan_perbaikan' => 'Perbaikan listrik yang konslet',
                'status_id' => $statuses->where('status_code', 'CONFIRMED')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Deni Wijaya',
                'alamat' => 'Jl. Thamrin No. 89, Jakarta',
                'no_handphone' => '084567890123',
                'tanggal_booking' => now()->addDays(2),
                'waktu_booking' => '14:00:00',
                'catatan_perbaikan' => 'Perbaikan AC yang tidak dingin',
                'status_id' => $statuses->where('status_code', 'ON_PROCESS')->first()->id ?? $statuses->first()->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Eka Putri',
                'alamat' => 'Jl. Kuningan No. 12, Jakarta',
                'no_handphone' => '085678901234',
                'tanggal_booking' => now()->addDays(1),
                'waktu_booking' => '11:00:00',
                'catatan_perbaikan' => 'Perbaikan pintu yang rusak',
                'status_id' => $statuses->where('status_code', 'COMPLETED')->first()->id ?? $statuses->first()->id,
            ],
        ];
        
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
        
        $this->command->info('Data booking berhasil dibuat!');
    }
}