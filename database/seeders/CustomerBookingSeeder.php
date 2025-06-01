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

        // Get all status IDs for new status codes
        $pendingStatus = $statuses->where('status_code', 'pending')->first();
        $waitingValidationDpStatus = $statuses->where('status_code', 'waiting_validation_dp')->first();
        $dpValidatedStatus = $statuses->where('status_code', 'dp_validated')->first();
        $inProgressStatus = $statuses->where('status_code', 'in_progress')->first();
        $doneStatus = $statuses->where('status_code', 'done')->first();
        $waitingPelunasanStatus = $statuses->where('status_code', 'waiting_pelunasan')->first();
        $waitingValidationPelunasanStatus = $statuses->where('status_code', 'waiting_validation_pelunasan')->first();
        $completedStatus = $statuses->where('status_code', 'completed')->first();
        $rejectedStatus = $statuses->where('status_code', 'rejected')->first();
        $canceledStatus = $statuses->where('status_code', 'canceled')->first();

        // Verify that all required statuses exist
        if (!$pendingStatus || !$waitingValidationDpStatus || !$dpValidatedStatus || 
            !$inProgressStatus || !$completedStatus) {
            $this->command->error('Required booking statuses not found. Please run BookingStatusSeeder first.');
            return;
        }

        $bookings = [
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Ahmad Fauzi',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-28',
                'waktu_booking' => '09:00',
                'catatan_perbaikan' => 'Peralatan rusak dan perlu perbaikan segera',
                'status_id' => $pendingStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Budi Santoso',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-28',
                'waktu_booking' => '10:30',
                'catatan_perbaikan' => 'Alat tidak berfungsi dengan baik',
                'status_id' => $waitingValidationDpStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Citra Dewi',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-28',
                'waktu_booking' => '13:00',
                'catatan_perbaikan' => 'Perlu penggantian komponen',
                'status_id' => $dpValidatedStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Deni Wijaya',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-28',
                'waktu_booking' => '14:30',
                'catatan_perbaikan' => 'Kerusakan parah pada sistem',
                'status_id' => $inProgressStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Eka Putri',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-28',
                'waktu_booking' => '16:00',
                'catatan_perbaikan' => 'Butuh perbaikan menyeluruh',
                'status_id' => $doneStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Fajar Nugraha',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-29',
                'waktu_booking' => '09:30',
                'catatan_perbaikan' => 'Perlu penggantian part',
                'status_id' => $waitingPelunasanStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Gita Lestari',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-29',
                'waktu_booking' => '11:00',
                'catatan_perbaikan' => 'Kerusakan pada sistem elektronik',
                'status_id' => $waitingValidationPelunasanStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Hadi Susanto',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-30',
                'waktu_booking' => '10:00',
                'catatan_perbaikan' => 'Peralatan sudah tua dan perlu diganti',
                'status_id' => $completedStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Indah Permata',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-30',
                'waktu_booking' => '13:30',
                'catatan_perbaikan' => 'Kerusakan pada komponen utama',
                'status_id' => $rejectedStatus->id,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->service_id,
                'nama_pemesan' => 'Joko Prabowo',
                'service_name' => $services->random()->title_service,
                'tanggal_booking' => '2025-05-31',
                'waktu_booking' => '15:00',
                'catatan_perbaikan' => 'Perbaikan darurat diperlukan',
                'status_id' => $canceledStatus->id,
            ],
        ];
        
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}