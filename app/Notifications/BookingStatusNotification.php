<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $statusName;
    protected $isForProvider;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $statusName, bool $isForProvider = false)
    {
        $this->booking = $booking;
        $this->statusName = $statusName;
        $this->isForProvider = $isForProvider;
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
        $mailMessage = (new MailMessage)
            ->from('tatafixtest123@gmail.com', 'TataFix Service')
            ->subject('Update Status Booking #' . $this->booking->id);

        if ($this->isForProvider) {
            return $this->buildProviderEmail($mailMessage);
        } else {
            return $this->buildCustomerEmail($mailMessage);
        }
    }

    /**
     * Build email content for customers
     */
    protected function buildCustomerEmail(MailMessage $mailMessage): MailMessage
    {
        $mailMessage->greeting('Halo ' . $this->booking->nama_pemesan . ',')
            ->line('Status booking layanan perbaikan Anda telah diperbarui.')
            ->line('ID Booking: #' . $this->booking->id)
            ->line('Layanan: ' . $this->booking->service->title_service)
            ->line('Status saat ini: ' . $this->statusName);

        // Add payment information if status is confirmed
        if ($this->statusName === 'Dikonfirmasi') {
            $mailMessage->line('Mohon segera melakukan pembayaran DP sebesar 50% dari total biaya:')
                ->line('Total Biaya: Rp ' . number_format($this->booking->service->base_price, 0, ',', '.'))
                ->line('DP (50%): Rp ' . number_format($this->booking->service->base_price * 0.5, 0, ',', '.'))
                ->line('Silakan transfer ke rekening berikut:')
                ->line('Bank BCA: 1234567890 a/n TataFix')
                ->line('Setelah melakukan pembayaran, silakan konfirmasi dengan membalas email ini dengan melampirkan bukti pembayaran.');
        }

        // Add payment information if status is completed
        if ($this->statusName === 'Selesai') {
            $mailMessage->line('Terima kasih telah menggunakan layanan TataFix.')
                ->line('Mohon segera melakukan pelunasan pembayaran:')
                ->line('Total Biaya: Rp ' . number_format($this->booking->service->base_price, 0, ',', '.'))
                ->line('Sisa Pembayaran (50%): Rp ' . number_format($this->booking->service->base_price * 0.5, 0, ',', '.'))
                ->line('Silakan transfer ke rekening berikut:')
                ->line('Bank BCA: 1234567890 a/n TataFix');
        }

        $mailMessage->action('Lihat Detail Booking', url('/customer/bookings/' . $this->booking->id))
            ->line('Terima kasih telah menggunakan layanan kami.');

        return $mailMessage;
    }

    /**
     * Build email content for service providers
     */
    protected function buildProviderEmail(MailMessage $mailMessage): MailMessage
    {
        $mailMessage->greeting('Halo ' . $this->booking->service->provider->name . ',')
            ->line('Ada pembaruan status untuk layanan perbaikan yang Anda sediakan.')
            ->line('ID Booking: #' . $this->booking->id)
            ->line('Layanan: ' . $this->booking->service->title_service)
            ->line('Status saat ini: ' . $this->statusName)
            ->line('Nama Pemesan: ' . $this->booking->nama_pemesan)
            ->line('Alamat: ' . $this->booking->alamat)
            ->line('No. Handphone: ' . $this->booking->no_handphone)
            ->line('Tanggal & Waktu: ' . $this->booking->tanggal_booking . ' ' . $this->booking->waktu_booking)
            ->line('Catatan: ' . $this->booking->catatan_perbaikan)
            ->action('Lihat Detail Booking', url('/provider/bookings/' . $this->booking->id))
            ->line('Terima kasih atas layanan Anda.');

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
        ];
    }
}