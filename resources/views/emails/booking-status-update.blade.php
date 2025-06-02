@component('mail::message')
# Update Status Booking

Halo {{ $booking->nama_pemesan }},

Status booking Anda telah berubah:

@component('mail::panel')
**Status Sebelumnya:** {{ $oldStatus->display_name }}

**Status Baru:** {{ $newStatus->display_name }}
@endcomponent

Detail Booking:

@component('mail::table')
| Field | Value |
| - | - |
| Nama Pemesan | {{ $booking->nama_pemesan }} |
| Layanan | {{ $booking->service->name }} |
| Tanggal Booking | {{ $booking->tanggal_booking->format('d M Y') }} |
| Jam Booking | {{ $booking->jam_booking }} |
@endcomponent

Terima kasih telah menggunakan layanan kami!

@component('mail::button', ['url' => config('app.url')])
Lihat Detail Booking
@endcomponent

Terima kasih,
{{ config('app.name') }}
@endcomponent
