<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\CustomerBookingNotification;
use App\Notifications\ProviderBookingNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    // Menentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'bookings';

    // Menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',           // ID user yang melakukan booking
        'service_id',        // ID layanan yang dibooking
        'nama_pemesan',      // Nama pemesan
        'alamat',            // Alamat pemesan
        'no_handphone',      // Nomor handphone pemesan
        'tanggal_booking',   // Tanggal booking
        'waktu_booking',     // Waktu booking
        'catatan_perbaikan', // Catatan perbaikan yang dibutuhkan
        'status_id',         // Status booking
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi dengan Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi dengan BookingStatus
    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id', 'status_id');
    }

    /**
     * Send notifications about status change to customer and provider
     *
     * @return void
     */
    public function sendStatusNotifications()
    {
        try {
            // Load relations if not already loaded
            $this->load([
                'user', 
                'service', 
                'service.provider', 
                'status'
            ]);

            // Validate required relations
            if (!$this->user) {
                Log::error('Missing user relation for booking notification', ['booking_id' => $this->id]);
                return;
            }

            if (!$this->service) {
                Log::error('Missing service relation for booking notification', ['booking_id' => $this->id]);
                return;
            }

            if (!$this->service->provider) {
                Log::error('Missing provider relation for booking notification', ['booking_id' => $this->id]);
                return;
            }

            if (!$this->status) {
                Log::error('Missing status relation for booking notification', ['booking_id' => $this->id]);
                return;
            }

            // Send notification to customer
            try {
                Notification::send($this->user, new CustomerBookingNotification($this, $this->status->display_name));
                Log::info('Customer notification sent successfully', [
                    'booking_id' => $this->id,
                    'customer_email' => $this->user->email,
                    'status' => $this->status->display_name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send customer booking notification', [
                    'booking_id' => $this->id,
                    'customer_email' => $this->user->email ?? 'N/A',
                    'error' => $e->getMessage()
                ]);
            }

            // Send notification to provider
            try {
                Notification::send($this->service->provider, new ProviderBookingNotification($this, $this->status->display_name));
                Log::info('Provider notification sent successfully', [
                    'booking_id' => $this->id,
                    'provider_email' => $this->service->provider->email,
                    'status' => $this->status->display_name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send provider booking notification', [
                    'booking_id' => $this->id,
                    'provider_email' => $this->service->provider->email ?? 'N/A',
                    'error' => $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Critical error in sendStatusNotifications', [
                'booking_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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