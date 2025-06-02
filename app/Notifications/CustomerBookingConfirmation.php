<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerBookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Ensure we're using the mail channel
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Load relations to ensure all necessary data is available
        $this->booking->load(['service', 'service.provider', 'user']);
        
        // Get the email address from the notifiable model
        $to = $notifiable->email ?? $this->booking->user->email ?? 'test@example.com';

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Konfirmasi Booking Layanan Anda #' . $this->booking->id)
            ->greeting('Halo ' . $this->booking->nama_pemesan . ',')
            ->line('Terima kasih telah melakukan booking layanan di TataFix.')
            ->line('Detail Booking Anda:')
            ->line('ID Booking: #' . $this->booking->id)
            ->line('Layanan: ' . $this->booking->service->title_service)
            ->line('Tanggal & Waktu: ' . date('d/m/Y', strtotime($this->booking->tanggal_booking)) . ' pukul ' . $this->booking->waktu_booking)
            ->line('Provider: ' . $this->booking->service->provider->name)
            ->line('')
            ->line('Silakan lakukan pembayaran DP sebesar 50% dari total biaya.')
            ->line('Setelah pembayaran berhasil, provider akan segera menghubungi Anda untuk konfirmasi.')
            ->action('Lihat Detail Booking', url('/customer/bookings/' . $this->booking->id))
            ->line('Terima kasih telah memilih TataFix sebagai solusi perbaikan Anda.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'service_name' => $this->booking->service->title_service,
            'booking_date' => $this->booking->tanggal_booking,
            'booking_time' => $this->booking->waktu_booking,
            'provider_name' => $this->booking->service->provider->name,
        ];
    }
}
