<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\BookingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookingLogs = [
            // booking 1
            [
                'booking_status_id' => BookingStatus::where('status_code', 'PENDING')->first()->id,
                'booking_id' => Booking::where('id', 1)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],

            // booking 2
            [
                'booking_status_id' => BookingStatus::where('status_code', 'PENDING')->first()->id,
                'booking_id' => Booking::where('id', 2)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'CONFIRMED')->first()->id,
                'booking_id' => Booking::where('id', 2)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],

            // booking 3
            [
                'booking_status_id' => BookingStatus::where('status_code', 'PENDING')->first()->id,
                'booking_id' => Booking::where('id', 3)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'CONFIRMED')->first()->id,
                'booking_id' => Booking::where('id', 3)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'ON_PROCESS')->first()->id,
                'booking_id' => Booking::where('id', 3)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],

            // booking 4
            [
                'booking_status_id' => BookingStatus::where('status_code', 'PENDING')->first()->id,
                'booking_id' => Booking::where('id', 4)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'CONFIRMED')->first()->id,
                'booking_id' => Booking::where('id', 4)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'ON_PROCESS')->first()->id,
                'booking_id' => Booking::where('id', 4)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'booking_status_id' => BookingStatus::where('status_code', 'COMPLETED')->first()->id,
                'booking_id' => Booking::where('id', 4)->first()->id,
                'created_at' => \Carbon\Carbon::now()->subDays(1),
            ],           
        ];

        foreach ($bookingLogs as $bookingLog) {
            BookingLog::create($bookingLog);
        }
    }
}
