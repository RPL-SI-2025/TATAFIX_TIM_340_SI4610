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

        $bookings = [
            [
                'user_id' => $user->id,
                'booking_status_id' => BookingStatus::where('status_code', 'PENDING')->first()->id,
                'service_id' => 1,
                'nama_pemesan' => $user->name,
                'alamat' => $user->address,
                'no_handphone' => $user->phone,
                'tanggal_booking' => now(),
                'waktu_booking' => now(),
                'catatan_perbaikan' => 'Perbaikan mesin',
            ],
            [
                'user_id' => $user->id,
                'booking_status_id' => BookingStatus::where('status_code', 'CONFIRMED')->first()->id,
                'service_id' => 1,
                'nama_pemesan' => $user->name,
                'alamat' => $user->address,
                'no_handphone' => $user->phone,
                'tanggal_booking' => now(),
                'waktu_booking' => now(),
                'catatan_perbaikan' => 'Perbaikan mesin',
            ],
            [
                'user_id' => $user->id,
                'booking_status_id' => BookingStatus::where('status_code', 'ON_PROCESS')->first()->id,
                'service_id' => 1,
                'nama_pemesan' => $user->name,
                'alamat' => $user->address,
                'no_handphone' => $user->phone,
                'tanggal_booking' => now(),
                'waktu_booking' => now(),
                'catatan_perbaikan' => 'Perbaikan mesin',
            ],
            [
                'user_id' => $user->id,
                'booking_status_id' => BookingStatus::where('status_code', 'COMPLETED')->first()->id,
                'service_id' => 1,
                'nama_pemesan' => $user->name,
                'alamat' => $user->address,
                'no_handphone' => $user->phone,
                'tanggal_booking' => now(),
                'waktu_booking' => now(),
                'catatan_perbaikan' => 'Perbaikan mesin',
            ],
        ];
        
        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
