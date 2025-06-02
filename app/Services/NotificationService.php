<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;

class NotificationService
{
    /**
     * Create an admin payment notification when a customer makes a payment.
     *
     * @param Booking $booking
     * @param string $paymentType
     * @return Notification|null
     */
    public function createAdminPaymentNotification(Booking $booking, string $paymentType = 'DP')
    {
        // Cari semua admin
        $admins = User::role('admin')->get();
        
        if ($admins->isEmpty()) {
            return null; // Tidak ada admin yang ditemukan
        }
        
        $notifications = [];
        
        foreach ($admins as $admin) {
            $notifications[] = Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pembayaran Baru Memerlukan Validasi',
                'message' => "Customer telah melakukan pembayaran {$paymentType} untuk booking #{$booking->id}. Silakan validasi pembayaran.",
                'type' => 'warning',
                'data' => [
                    'booking_id' => $booking->id,
                    'service_name' => $booking->service->title_service,
                    'booking_date' => $booking->tanggal_booking,
                    'booking_time' => $booking->waktu_booking,
                    'customer_name' => $booking->user->name,
                    'payment_type' => $paymentType
                ],
                'link' => url('/admin/bookings'), // Link ke halaman admin bookings
                'is_read' => false
            ]);
        }
        
        return $notifications[0] ?? null; // Return first notification or null
    }
    
    /**
     * Create a booking confirmation notification.
     *
     * @param User $user
     * @param Booking $booking
     * @return Notification
     */
    public function createBookingConfirmation(User $user, Booking $booking)
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => 'Booking Berhasil Dibuat',
            'message' => "Booking layanan #{$booking->id} telah berhasil dibuat. Silakan lakukan pembayaran DP untuk melanjutkan proses.",
            'type' => 'info',
            'data' => [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->title_service,
                'booking_date' => $booking->tanggal_booking,
                'booking_time' => $booking->waktu_booking,
            ],
            'link' => route('booking.show', $booking->id)
        ]);
    }
    
    /**
     * Create a payment verification notification.
     *
     * @param User $user
     * @param Booking $booking
     * @param string $status
     * @return Notification
     */
    public function createPaymentVerification(User $user, Booking $booking, string $status = 'validated')
    {
        $title = $status === 'validated' 
            ? 'Pembayaran Berhasil Diverifikasi' 
            : 'Pembayaran Ditolak';
            
        $message = $status === 'validated'
            ? "Pembayaran untuk booking #{$booking->id} telah berhasil diverifikasi. Proses booking akan dilanjutkan."
            : "Pembayaran untuk booking #{$booking->id} ditolak. Silakan periksa detail pembayaran Anda.";
            
        $type = $status === 'validated' ? 'success' : 'error';
        
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->title_service,
                'booking_date' => $booking->tanggal_booking,
                'booking_time' => $booking->waktu_booking,
                'payment_status' => $status
            ],
            'link' => route('booking.show', $booking->id)
        ]);
    }
    
    /**
     * Create a booking status update notification.
     *
     * @param User $user
     * @param Booking $booking
     * @param string $statusName
     * @return Notification
     */
    public function createBookingStatusUpdate(User $user, Booking $booking, string $statusName)
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => 'Status Booking Diperbarui',
            'message' => "Status booking #{$booking->id} telah diperbarui menjadi '{$statusName}'.",
            'type' => 'info',
            'data' => [
                'booking_id' => $booking->id,
                'service_name' => $booking->service->title_service,
                'booking_date' => $booking->tanggal_booking,
                'booking_time' => $booking->waktu_booking,
                'status' => $statusName
            ],
            'link' => route('booking.show', $booking->id)
        ]);
    }
}
