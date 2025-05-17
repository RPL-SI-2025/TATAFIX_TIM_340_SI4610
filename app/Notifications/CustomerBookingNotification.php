<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $statusName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $statusName)
    {
        $this->booking = $booking;
        $this->statusName = $statusName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Load relations to ensure all necessary data is available
        $this->booking->load(['service', 'service.provider', 'status']);

        $mailMessage = (new MailMessage)
            ->from('tatafixtest123@gmail.com', 'TataFix Service')
            ->subject('Update Status Booking #' . $this->booking->id)
            ->greeting('Halo ' . $this->booking->nama_pemesan . ',')
            ->line('Status booking layanan perbaikan Anda telah diperbarui.')
            ->line('Detail Booking:')
            ->line('ID Booking: #' . $this->booking->id)
            ->line('Layanan: ' . $this->booking->service->title_service)
            ->line('Status saat ini: ' . $this->statusName);

        // Tambahkan instruksi pembayaran untuk status tertentu
        switch ($this->statusName) {
            case 'Dikonfirmasi':
                $mailMessage->line('Mohon segera melakukan pembayaran DP (50% dari total biaya):')
                    ->line('Total Biaya: Rp ' . number_format($this->booking->service->base_price, 0, ',', '.'))
                    ->line('DP (50%): Rp ' . number_format($this->booking->service->base_price * 0.5, 0, ',', '.'))
                    ->line('Silakan transfer ke rekening berikut:')
                    ->line('Bank BCA: 1234567890 a/n TataFix')
                    ->line('Setelah melakukan pembayaran, silakan konfirmasi dengan membalas email ini dengan melampirkan bukti pembayaran.');
                break;

            case 'Selesai':
                $mailMessage->line('Terima kasih telah menggunakan layanan TataFix.')
                    ->line('Mohon segera melakukan pelunasan pembayaran:')
                    ->line('Total Biaya: Rp ' . number_format($this->booking->service->base_price, 0, ',', '.'))
                    ->line('Sisa Pembayaran (50%): Rp ' . number_format($this->booking->service->base_price * 0.5, 0, ',', '.'))
                    ->line('Silakan transfer ke rekening berikut:')
                    ->line('Bank BCA: 1234567890 a/n TataFix');
                break;
        }

        $mailMessage->action('Lihat Detail Booking', url('/customer/bookings/' . $this->booking->id))
            ->line('Terima kasih telah menggunakan layanan kami.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->statusName,
            'service_name' => $this->booking->service->title_service,
        ];
    }
}