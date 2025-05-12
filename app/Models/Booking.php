<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\BookingStatusNotification;
use Illuminate\Support\Facades\Notification;

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

    /**
     * Send notifications about status change to customer and provider
     *
     * @return void
     */
    public function sendStatusNotifications()
    {
        // Load status relation if not already loaded
        if (!$this->relationLoaded('status')) {
            $this->load('status');
        }

        // Load service and provider relations if not already loaded
        if (!$this->relationLoaded('service') || !$this->relationLoaded('service.provider')) {
            $this->load(['service', 'service.provider']);
        }

        // Get status display name
        $statusName = $this->status->display_name;

        // Send notification to customer
        $customer = $this->user;
        if ($customer) {
            Notification::send($customer, new BookingStatusNotification($this, $statusName, false));
        }

        // Send notification to provider
        $provider = $this->service->provider;
        if ($provider) {
            Notification::send($provider, new BookingStatusNotification($this, $statusName, true));
        }
    }
}