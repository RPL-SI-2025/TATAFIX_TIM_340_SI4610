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
        // Get all status IDs for new status codes
        $pendingStatus = BookingStatus::where('status_code', 'pending')->first();
        $waitingValidationDpStatus = BookingStatus::where('status_code', 'waiting_validation_dp')->first();
        $dpValidatedStatus = BookingStatus::where('status_code', 'dp_validated')->first();
        $inProgressStatus = BookingStatus::where('status_code', 'in_progress')->first();
        $doneStatus = BookingStatus::where('status_code', 'done')->first();
        $waitingPelunasanStatus = BookingStatus::where('status_code', 'waiting_pelunasan')->first();
        $waitingValidationPelunasanStatus = BookingStatus::where('status_code', 'waiting_validation_pelunasan')->first();
        $completedStatus = BookingStatus::where('status_code', 'completed')->first();

        if (!$pendingStatus || !$waitingValidationDpStatus || !$dpValidatedStatus || 
            !$inProgressStatus || !$doneStatus || !$completedStatus) {
            $this->command->error('BookingStatusSeeder must be run before BookingLogSeeder');
            return;
        }

        $bookingLogs = [
            // booking 1 - still in pending status
            [
                'status_id' => $pendingStatus->id,
                'booking_id' => 1,
                'created_at' => \Carbon\Carbon::now()->subDays(1),
            ],

            // booking 2 - waiting for DP validation
            [
                'status_id' => $pendingStatus->id,
                'booking_id' => 2,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'status_id' => $waitingValidationDpStatus->id,
                'booking_id' => 2,
                'created_at' => \Carbon\Carbon::now()->subDays(1),
            ],

            // booking 3 - DP validated, ready for work
            [
                'status_id' => $pendingStatus->id,
                'booking_id' => 3,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
            [
                'status_id' => $waitingValidationDpStatus->id,
                'booking_id' => 3,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'status_id' => $dpValidatedStatus->id,
                'booking_id' => 3,
                'created_at' => \Carbon\Carbon::now()->subDays(1),
            ],

            // booking 4 - work in progress
            [
                'status_id' => $pendingStatus->id,
                'booking_id' => 4,
                'created_at' => \Carbon\Carbon::now()->subDays(4),
            ],
            [
                'status_id' => $waitingValidationDpStatus->id,
                'booking_id' => 4,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
            [
                'status_id' => $dpValidatedStatus->id,
                'booking_id' => 4,
                'created_at' => \Carbon\Carbon::now()->subDays(2),
            ],
            [
                'status_id' => $inProgressStatus->id,
                'booking_id' => 4,
                'created_at' => \Carbon\Carbon::now()->subDays(1),
            ],

            // booking 5 - completed full flow
            [
                'status_id' => $pendingStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(10),
            ],
            [
                'status_id' => $waitingValidationDpStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(9),
            ],
            [
                'status_id' => $dpValidatedStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(8),
            ],
            [
                'status_id' => $inProgressStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(7),
            ],
            [
                'status_id' => $doneStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(6),
            ],
            [
                'status_id' => $waitingPelunasanStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(5),
            ],
            [
                'status_id' => $waitingValidationPelunasanStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(4),
            ],
            [
                'status_id' => $completedStatus->id,
                'booking_id' => 5,
                'created_at' => \Carbon\Carbon::now()->subDays(3),
            ],
        ];

        foreach ($bookingLogs as $bookingLog) {
            BookingLog::create($bookingLog);
        }
    }
}
