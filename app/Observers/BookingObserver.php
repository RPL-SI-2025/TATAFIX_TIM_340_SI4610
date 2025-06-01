<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "saving" event.
     */
    public function saving(Booking $booking): void
    {
        // Jika status_id berubah, pastikan status_code juga diperbarui
        if ($booking->isDirty('status_id')) {
            $statusId = $booking->status_id;
            
            // Cari status_code yang sesuai dengan status_id
            $status = BookingStatus::find($statusId);
            
            if ($status) {
                $booking->status_code = $status->status_code;
                Log::info("BookingObserver: Booking #{$booking->id} status_code diperbarui ke {$status->status_code} berdasarkan status_id {$statusId}");
            } else {
                Log::warning("BookingObserver: Tidak dapat menemukan status dengan ID {$statusId} untuk Booking #{$booking->id}");
            }
        }
    }
}
