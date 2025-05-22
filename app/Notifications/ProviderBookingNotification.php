<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProviderBookingNotification extends Notification implements ShouldQueue
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
        $this->booking->load(['service', 'service.provider', 'user', 'status']);

        $mailMessage = (new MailMessage)
            ->from('tatafixtest123@gmail.com', 'TataFix Service')
            ->subject('Update Status Booking Layanan #' . $this->booking->id)
            ->greeting('Halo ' . $this->booking->service->provider->name . ',')
            ->line('Ada pembaruan status untuk layanan perbaikan yang Anda sediakan.')
            ->line('Detail Booking:')
            ->line('ID Booking: #' . $this->booking->id)
            ->line('Layanan: ' . $this->booking->service->title_service)
            ->line('Status saat ini: ' . $this->statusName)
            ->line('')
            ->line('Informasi Pelanggan:')
            ->line('Nama Pemesan: ' . $this->booking->nama_pemesan)
            ->line('Alamat: ' . $this->booking->alamat)
            ->line('No. Handphone: ' . $this->booking->no_handphone)
            ->line('Tanggal & Waktu: ' . date('d/m/Y', strtotime($this->booking->tanggal_booking)) . ' pukul ' . $this->booking->waktu_booking)
            ->line('Catatan Perbaikan: ' . $this->booking->catatan_perbaikan);

        // Tambahkan informasi spesifik berdasarkan status
        switch ($this->statusName) {
            case 'Menunggu Konfirmasi':
                $mailMessage->line('')
                    ->line('Silakan konfirmasi booking ini melalui sistem atau hubungi pelanggan untuk koordinasi lebih lanjut.');
                break;

            case 'Dikonfirmasi':
                $mailMessage->line('')
                    ->line('Booking telah dikonfirmasi. Pelanggan akan melakukan pembayaran DP sebesar 50%.')
                    ->line('Silakan bersiap untuk melakukan perbaikan sesuai jadwal yang telah ditentukan.');
                break;

            case 'Sedang Diproses':
                $mailMessage->line('')
                    ->line('Pekerjaan sedang dalam proses. Pastikan untuk memberikan update berkala kepada pelanggan.');
                break;

            case 'Selesai':
                $mailMessage->line('')
                    ->line('Pekerjaan telah selesai. Pelanggan akan melakukan pembayaran sisa sebesar 50%.')
                    ->line('Terima kasih atas layanan yang telah diberikan.');
                break;

            case 'Dibatalkan':
                $mailMessage->line('')
                    ->line('Booking ini telah dibatalkan. Tidak ada tindakan lebih lanjut yang diperlukan.');
                break;
        }

        $mailMessage->action('Lihat Detail Booking', url('/provider/bookings/' . $this->booking->id))
            ->line('Terima kasih atas layanan Anda di TataFix.');

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
            'customer_name' => $this->booking->nama_pemesan,
        ];
    }
}