<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\CustomerBookingNotification;
use App\Notifications\ProviderBookingNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',           // ID user yang melakukan booking
        'status_id',         // ID status booking
        'service_id',        // ID layanan yang dibooking
        'nama_pemesan',      // Nama pemesan
        'alamat',            // Alamat pemesan
        'no_handphone',      // Nomor handphone pemesan
        'tanggal_booking',   // Tanggal booking
        'waktu_booking',     // Waktu booking
        'catatan_perbaikan'  // Catatan perbaikan yang dibutuhkan
    ];

    // Relasi yang akan selalu di-load
    protected $with = ['bookingStatus', 'service', 'user', 'bookingLogs'];
    public function status()
    {
    return $this->belongsTo(BookingStatus::class, 'status_id', 'id');
    }


    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    // Relasi dengan Booking Status
    public function bookingStatus()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id', 'id');
    }

    /*
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    * @return \App\Models\BookingLog
    */
    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class);
    }

    /**
     * Relasi dengan BookingStatus
     * Menggunakan 'id' sebagai foreign key karena BookingStatus menggunakan 'id' sebagai primary key
     */
    // public function status()
    // {
    //     return $this->belongsTo(BookingStatus::class, 'status_id', 'id');
    // }

    /**
     * Relasi dengan Payment
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Send notifications about status change to customer and provider
     *
     * @return void
     */
    public function sendStatusNotifications()
    {
        try {
            // Load relations dengan error handling untuk setiap relasi
            $this->load(['user', 'service.provider', 'status']);

            // Validate user relation
            if (!$this->user) {
                Log::error('Missing user relation for booking notification', [
                    'booking_id' => $this->id,
                    'user_id' => $this->user_id
                ]);
                return;
            }

            // Validate service relation
            if (!$this->service) {
                Log::error('Missing service relation for booking notification', [
                    'booking_id' => $this->id,
                    'service_id' => $this->service_id
                ]);
                return;
            }

            // Validate provider relation
            if (!$this->service->provider) {
                Log::error('Missing provider relation for booking notification', [
                    'booking_id' => $this->id,
                    'service_id' => $this->service_id
                ]);
                return;
            }

            // Validate status relation
            if (!$this->status) {
                Log::error('Missing status relation for booking notification', [
                    'booking_id' => $this->id,
                    'status_id' => $this->status_id
                ]);
                return;
            }

            Log::info('Sending booking notifications', [
                'booking_id' => $this->id,
                'status' => $this->status->display_name,
                'customer_email' => $this->user->email,
                'provider_email' => $this->service->provider->email
            ]);

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
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
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
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
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
        $oldStatusId = $this->status_id;
        
        $this->status_id = $statusId;
        $this->save();
        
        if ($oldStatusId != $statusId) {
            $this->sendStatusNotifications();
        }
    }
}