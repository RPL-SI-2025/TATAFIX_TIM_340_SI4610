<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\CustomerBookingNotification;
use App\Notifications\ProviderBookingNotification;
use Illuminate\Support\Facades\Notification;

class Booking extends Model
{
    // Existing model code...

    /**
     * Send notifications about status change to customer and provider
     *
     * @return void
     */
    public function sendStatusNotifications()
    {
        $this->load([
            'user', 
            'service', 
            'service.provider', 
            'status'
        ]);

        // Validate required relations
        if (!$this->user || !$this->service || !$this->service->provider || !$this->status) {
            \Log::error('Incomplete booking data for notifications', [
                'booking_id' => $this->id,
                'user' => $this->user ? 'exists' : 'missing',
                'service' => $this->service ? 'exists' : 'missing',
                'provider' => $this->service->provider ? 'exists' : 'missing',
                'status' => $this->status ? 'exists' : 'missing',
            ]);
            return;
        }

        // notifikasi ke customer
        try {
            Notification::send($this->user, new CustomerBookingNotification($this, $this->status->display_name));
        } catch (\Exception $e) {
            \Log::error('Failed to send customer booking notification', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }

        // notifikasi ke provider
        try {
            Notification::send($this->service->provider, new ProviderBookingNotification($this, $this->status->display_name));
        } catch (\Exception $e) {
            \Log::error('Failed to send provider booking notification', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update booking status and send notifications
     *
     * @param int $statusId
     * @return void
     */
    public function updateStatus(int $statusId)
    {
        // Get the old status for comparison
        $oldStatusId = $this->status_id;
        
        // Update the status
        $this->status_id = $statusId;
        $this->save();
        
        // If the status actually changed, send notifications
        if ($oldStatusId != $statusId) {
            $this->sendStatusNotifications();
        }
    }
}