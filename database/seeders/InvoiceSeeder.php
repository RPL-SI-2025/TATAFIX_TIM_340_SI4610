<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Get some users to associate with invoices
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->take(5)->get();
        
        if ($users->isEmpty()) {
            // If no users found, create a dummy user
            $user = User::firstOrCreate(
                ['email' => 'customer@example.com'],
                [
                    'name' => 'Sample Customer',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
            $users = collect([$user]);
        }
        
        // Get bookings with various statuses to associate with invoices
        $bookings = Booking::take(5)->get();
        
        if ($bookings->isEmpty()) {
            $this->command->error('No bookings found. Please run BookingSeeder first.');
            return;
        }
        
        // Create sample invoices
        foreach ($bookings as $index => $booking) {
            // Create a sample invoice with incrementing numbers
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(($index + 1), 4, '0', STR_PAD_LEFT);
            
            // Determine invoice status based on booking status
            $invoiceStatus = 'pending';
            if ($booking->status) {
                if (in_array($booking->status->status_code, ['completed', 'dp_validated', 'waiting_pelunasan'])) {
                    $invoiceStatus = 'paid';
                } elseif (in_array($booking->status->status_code, ['canceled', 'rejected'])) {
                    $invoiceStatus = 'cancelled';
                }
            }
            
            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'nama_pemesan' => $booking->nama_pemesan,
                'no_handphone' => $booking->no_handphone ?? '08123456789' . $index,
                'alamat' => $booking->alamat ?? 'Jl. Sample Address No. ' . ($index + 1) . ', Jakarta',
                'jenis_layanan' => $booking->service ? $booking->service->title_service : $this->getRandomService($index),
                'down_payment' => $this->getDownPayment($index),
                'biaya_pelunasan' => $this->getPelunasan($index),
                'total' => $this->getTotal($index),
                'status' => $invoiceStatus,
                'tanggal_invoice' => Carbon::parse($booking->tanggal_booking)->subDays(rand(1, 5)),
            ]);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
    
    /**
     * Get a random service name based on index
     */
    private function getRandomService($index)
    {
        $services = [
            'Perbaikan TV',
            'Perbaikan Kulkas',
            'Perbaikan Mesin Cuci',
            'Perbaikan Kran',
            'Instalasi Listrik Baru',
        ];
        
        return $services[$index % count($services)];
    }
    
    /**
     * Get down payment amount based on index
     */
    private function getDownPayment($index)
    {
        $baseAmounts = [150000, 200000, 180000, 100000, 500000];
        $baseAmount = $baseAmounts[$index % count($baseAmounts)];
        
        return $baseAmount * 0.3; // 30% down payment
    }
    
    /**
     * Get pelunasan amount based on index
     */
    private function getPelunasan($index)
    {
        $baseAmounts = [150000, 200000, 180000, 100000, 500000];
        $baseAmount = $baseAmounts[$index % count($baseAmounts)];
        
        return $baseAmount * 0.7; // 70% remaining payment
    }
    
    /**
     * Get total amount based on index
     */
    private function getTotal($index)
    {
        $baseAmounts = [150000, 200000, 180000, 100000, 500000];
        return $baseAmounts[$index % count($baseAmounts)];
    }
}
