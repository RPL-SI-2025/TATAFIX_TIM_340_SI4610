<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingStatus;

class BookingStatusSeeder extends Seeder
{
public function run(): void
{
    // Tambahkan status baru
    $statuses = [
        ['customer' => 'John Doe', 'email' => 'john@example.com', 'status' => 'Pending'],
        ['customer' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'Selesai'],
        ['customer' => 'Alice Johnson', 'email' => 'alice@example.com', 'status' => 'Dibatalkan'],
        ['customer' => 'Bob Brown', 'email' => 'bob@example.com', 'status' => 'Pending'],
        ['customer' => 'Charlie Davis', 'email' => 'charlie@example.com', 'status' => 'Selesai'],
        ['status_code' => 'WAITING_PAYMENT', 'display_name' => 'Menunggu Pembayaran'],
        ['status_code' => 'WAITING_VALIDATION', 'display_name' => 'Menunggu Validasi'],
    ];

    foreach ($bookings as $booking) {
        BookingStatus::create($booking);
        echo "Inserted booking for customer: " . $booking['customer'] . "\n";
    }
}
}

];