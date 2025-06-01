<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'nama_pemesan',
        'service_name',
        'tanggal_booking',
        'waktu_booking',
        'catatan_perbaikan',
        'status_id',
        'status_code',
        'dp_amount',
        'final_amount',
        'assigned_worker_id',
        'completed_at',
        'dp_paid_at',
        'final_paid_at'
    ];

    // Relasi yang akan selalu di-load
    protected $with = ['status', 'service', 'user', 'bookingLogs'];


    protected $casts = [
        'tanggal_booking' => 'date',
        'waktu_booking' => 'datetime:H:i',
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    // Relasi dengan Booking Status - DEPRECATED, gunakan status() sebagai gantinya
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
     * Relationship dengan BookingStatus
     */
    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }
    
    /**
     * Accessor untuk memastikan status_code selalu sinkron dengan status_id
     */
    public function getStatusCodeAttribute($value)
    {
        // Jika status_code sudah ada dan valid, gunakan nilai tersebut
        if (!empty($value)) {
            return $value;
        }
        
        // Jika status_code kosong tapi status_id ada, ambil dari relasi
        if ($this->status) {
            return $this->status->status_code;
        }
        
        // Fallback ke nilai asli jika tidak ada relasi
        return $value;
    }
    
    /**
     * Mutator untuk memastikan status_code selalu disimpan dalam lowercase
     */
    public function setStatusCodeAttribute($value)
    {
        $this->attributes['status_code'] = strtolower($value);
    }

    /**
     * Relationship dengan BookingParameter
     */
    public function bookingParameters()
    {
        return $this->hasMany(BookingParameter::class);
    }
    
    /**
     * Relationship dengan Payment
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Relationship dengan Tukang/Worker (User dengan role tukang)
     */
    public function tukang()
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    /**
     * Method untuk mengirim notifikasi status booking
     */
    public function sendStatusNotifications()
    {
        try {
            // Load relasi yang diperlukan
            $this->load(['user', 'service.provider', 'status']);

            // Kirim notifikasi ke customer
            $this->sendCustomerNotification();

            // Kirim notifikasi ke provider/tukang
            if ($this->service && $this->service->provider) {
                $this->sendProviderNotification();
            }

            // Kirim notifikasi ke admin
            $this->sendAdminNotification();

            Log::info('Booking notifications sent successfully', [
                'booking_id' => $this->id,
                'status' => $this->status->status_code
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking notifications', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Kirim notifikasi ke customer
     */
    private function sendCustomerNotification()
    {
        if (!$this->user || !$this->user->email) {
            return;
        }

        $subject = $this->getNotificationSubject('customer');
        $message = $this->getNotificationMessage('customer');

        try {
            Mail::send('emails.booking-notification', [
                'booking' => $this,
                'message' => $message,
                'user' => $this->user
            ], function($mail) use ($subject) {
                $mail->to($this->user->email, $this->user->name)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send customer notification', [
                'booking_id' => $this->id,
                'user_email' => $this->user->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Kirim notifikasi ke provider/tukang
     */
    private function sendProviderNotification()
    {
        if (!$this->service->provider || !$this->service->provider->email) {
            return;
        }

        $subject = $this->getNotificationSubject('provider');
        $message = $this->getNotificationMessage('provider');

        try {
            Mail::send('emails.booking-notification', [
                'booking' => $this,
                'message' => $message,
                'user' => $this->service->provider
            ], function($mail) use ($subject) {
                $mail->to($this->service->provider->email, $this->service->provider->name)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send provider notification', [
                'booking_id' => $this->id,
                'provider_email' => $this->service->provider->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Kirim notifikasi ke admin
     */
    private function sendAdminNotification()
    {
        // Ambil semua admin
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            if (!$admin->email) continue;

            $subject = $this->getNotificationSubject('admin');
            $message = $this->getNotificationMessage('admin');

            try {
                Mail::send('emails.booking-notification', [
                    'booking' => $this,
                    'message' => $message,
                    'user' => $admin
                ], function($mail) use ($subject, $admin) {
                    $mail->to($admin->email, $admin->name)
                         ->subject($subject);
                });
            } catch (\Exception $e) {
                Log::error('Failed to send admin notification', [
                    'booking_id' => $this->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Generate subject email berdasarkan role
     */
    private function getNotificationSubject($role)
    {
        $statusName = $this->status->display_name;
        
        switch ($role) {
            case 'customer':
                return "Status Booking Anda: {$statusName}";
            case 'provider':
                return "Booking Baru/Update: {$statusName}";
            case 'admin':
                return "Notifikasi Booking: {$statusName}";
            default:
                return "Notifikasi Booking";
        }
    }

    /**
     * Generate pesan email berdasarkan role
     */
    private function getNotificationMessage($role)
    {
        $bookingId = $this->id;
        $serviceName = $this->service->title_service ?? 'Layanan';
        $statusName = $this->status->display_name;
        $customerName = $this->nama_pemesan;
        
        switch ($role) {
            case 'customer':
                return "Halo {$customerName},\n\nStatus booking Anda untuk layanan '{$serviceName}' telah diperbarui menjadi: {$statusName}.\n\nID Booking: {$bookingId}\nTanggal: {$this->tanggal_booking->format('d/m/Y')}\nWaktu: {$this->waktu_booking}\n\nTerima kasih telah menggunakan layanan kami.";
                
            case 'provider':
                return "Halo,\n\nAda booking baru atau update status untuk layanan '{$serviceName}'.\n\nID Booking: {$bookingId}\nCustomer: {$customerName}\nStatus: {$statusName}\nTanggal: {$this->tanggal_booking->format('d/m/Y')}\nWaktu: {$this->waktu_booking}\n\nSilakan login ke dashboard untuk melihat detail lengkap.";
                
            case 'admin':
                return "Ada aktivitas booking yang memerlukan perhatian.\n\nID Booking: {$bookingId}\nLayanan: {$serviceName}\nCustomer: {$customerName}\nStatus: {$statusName}\nTanggal: {$this->tanggal_booking->format('d/m/Y')}\n\nSilakan login ke admin panel untuk melihat detail.";
                
            default:
                return "Status booking telah diperbarui.";
        }
    }

    /**
     * Accessor untuk format tanggal yang mudah dibaca
     */
    public function getTanggalBookingFormattedAttribute()
    {
        return $this->tanggal_booking->format('d F Y');
    }

    /**
     * Accessor untuk format waktu yang mudah dibaca
     */
    public function getWaktuBookingFormattedAttribute()
    {
        return date('H:i', strtotime($this->waktu_booking));
    }
}