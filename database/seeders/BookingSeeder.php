<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'customer@tatafix.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Customer',
                'email' => 'customer@tatafix.com',
                'password' => Hash::make('password'),
            ]);
        }

        // Make sure all required statuses exist
        $pendingStatus = BookingStatus::where('status_code', 'pending')->first();
        $waitingValidationDpStatus = BookingStatus::where('status_code', 'waiting_validation_dp')->first();
        $dpValidatedStatus = BookingStatus::where('status_code', 'dp_validated')->first();
        $inProgressStatus = BookingStatus::where('status_code', 'in_progress')->first();
        $doneStatus = BookingStatus::where('status_code', 'done')->first();
        $completedStatus = BookingStatus::where('status_code', 'completed')->first();

        if (!$pendingStatus || !$waitingValidationDpStatus || !$dpValidatedStatus || 
            !$inProgressStatus || !$doneStatus || !$completedStatus) {
            $this->command->error('BookingStatusSeeder must be run before BookingSeeder');
            return;
        }

        $bookings = [
            [
                'user_id' => $user->id,
                'status_id' => $pendingStatus->id,
                'service_id' => 1,
                'nama_pemesan' => $user->name,
                'service_name' => 'Perbaikan Mesin Cuci',
                'tanggal_booking' => now(),
                'waktu_booking' => '09:00',
                'catatan_perbaikan' => 'Perbaikan mesin cuci yang tidak berputar',
            ],
            [
                'user_id' => $user->id,
                'status_id' => $waitingValidationDpStatus->id,
                'service_id' => 2,
                'nama_pemesan' => $user->name,
                'service_name' => 'Perbaikan AC',
                'tanggal_booking' => now()->addDays(1),
                'waktu_booking' => '13:00',
                'catatan_perbaikan' => 'AC tidak dingin dan mengeluarkan bunyi berisik',
            ],
            [
                'user_id' => $user->id,
                'status_id' => $dpValidatedStatus->id,
                'service_id' => 3,
                'nama_pemesan' => $user->name,
                'service_name' => 'Perbaikan Kulkas',
                'tanggal_booking' => now()->addDays(2),
                'waktu_booking' => '10:00',
                'catatan_perbaikan' => 'Kulkas tidak dingin dan mengeluarkan air',
            ],
            [
                'user_id' => $user->id,
                'status_id' => $inProgressStatus->id,
                'service_id' => 4,
                'nama_pemesan' => $user->name,
                'service_name' => 'Perbaikan TV',
                'tanggal_booking' => now()->subDays(1),
                'waktu_booking' => '14:00',
                'catatan_perbaikan' => 'TV tidak menyala sama sekali',
            ],
            [
                'user_id' => $user->id,
                'status_id' => $completedStatus->id,
                'service_id' => 5,
                'nama_pemesan' => $user->name,
                'service_name' => 'Perbaikan Kompor Gas',
                'tanggal_booking' => now()->subDays(5),
                'waktu_booking' => '11:00',
                'catatan_perbaikan' => 'Kompor gas tidak menyala',
            ],
        ];
        
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
