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
        'alamat',
        'waktu_booking',
        'catatan_perbaikan',
        'status_id',
        'status_code',
        'dp_amount',
        'final_amount',
        'assigned_worker_id',
        'completed_at',
        'dp_paid_at',
        'final_paid_at',
        'rating',
        'feedback'
    ];

    protected $with = ['status', 'service', 'user', 'bookingLogs'];

    protected $casts = [
        'tanggal_booking' => 'date',
        'waktu_booking' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function bookingStatus()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id', 'id');
    }

    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class);
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function getStatusCodeAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        
        if ($this->status) {
            return $this->status->status_code;
        }
        
        return $value;
    }

    public function setStatusCodeAttribute($value)
    {
        $this->attributes['status_code'] = strtolower($value);
    }

    public function bookingParameters()
    {
        return $this->hasMany(BookingParameter::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function tukang()
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    public function sendStatusNotifications()
    {
        try {
            $this->load(['user', 'service.provider', 'status']);

            $oldStatus = $this->status;
            $newStatus = BookingStatus::find($this->status_id);
            
            $this->status()->associate($newStatus);
            $this->save();

            $this->sendCustomerNotification();

            if ($this->service && $this->service->provider) {
                $this->sendProviderNotification();
            }

            $this->sendAdminNotification();

            Log::info('Booking notifications sent successfully', [
                'booking_id' => $this->id,
                'status' => $newStatus->status_code
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking notifications', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function sendCustomerNotification()
    {
        if (!$this->user || !$this->user->email) {
            return;
        }

        try {
            Mail::to($this->user->email)
                ->send(new \App\Mail\BookingStatusUpdate(
                    $this,
                    $this->status,
                    $this->status
                ));
            
            Log::info('Customer notification sent successfully', [
                'booking_id' => $this->id,
                'customer_email' => $this->user->email,
                'status' => $this->status->display_name
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customer notification', [
                'booking_id' => $this->id,
                'customer_email' => $this->user->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function sendProviderNotification()
    {
        if (!$this->service->provider || !$this->service->provider->email) {
            return;
        }

        try {
            Mail::to($this->service->provider->email)
                ->send(new \App\Mail\BookingStatusUpdate(
                    $this,
                    $this->status,
                    $this->status
                ));
        } catch (\Exception $e) {
            Log::error('Failed to send provider notification', [
                'booking_id' => $this->id,
                'provider_email' => $this->service->provider->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function sendAdminNotification()
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            if (!$admin->email) continue;

            try {
                Mail::to($admin->email)
                    ->send(new \App\Mail\BookingStatusUpdate(
                        $this,
                        $this->status,
                        $this->status
                    ));
            } catch (\Exception $e) {
                Log::error('Failed to send admin notification', [
                    'booking_id' => $this->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function getNotificationSubject($role)
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

    public function getNotificationMessage($role)
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

    public function getTanggalBookingFormattedAttribute()
    {
        return $this->tanggal_booking->format('d F Y');
    }

    public function getWaktuBookingFormattedAttribute()
    {
        return date('H:i', strtotime($this->waktu_booking));
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
