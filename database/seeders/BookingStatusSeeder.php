<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingStatus;

class BookingStatusSeeder extends Seeder
{
public function run(): void
{
    $bookings = [
        ['customer' => 'John Doe', 'email' => 'john@example.com', 'status' => 'Pending'],
        ['customer' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'Selesai'],
        ['customer' => 'Alice Johnson', 'email' => 'alice@example.com', 'status' => 'Dibatalkan'],
        ['customer' => 'Bob Brown', 'email' => 'bob@example.com', 'status' => 'Pending'],
        ['customer' => 'Charlie Davis', 'email' => 'charlie@example.com', 'status' => 'Selesai'],
    ];

    foreach ($bookings as $booking) {
        BookingStatus::create($booking);
        echo "Inserted booking for customer: " . $booking['customer'] . "\n";
    }
}
}