<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
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
        
        // Create sample invoices
        foreach ($users as $index => $user) {
            // Create a sample invoice with incrementing numbers
            $invoiceNumber = '#' . (735866 + $index);
            
            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'booking_id' => 1, // Set to null to avoid foreign key constraint
                'user_id' => $user->id,
                'nama_pemesan' => $user->name,
                'no_handphone' => '08123456789' . $index,
                'alamat' => 'Jl. Sample Address No. ' . ($index + 1) . ', Jakarta',
                'jenis_layanan' => $this->getRandomService($index),
                'down_payment' => $this->getDownPayment($index),
                'biaya_pelunasan' => $this->getPelunasan($index),
                'total' => $this->getTotal($index),
                'status' => $index % 3 == 0 ? 'paid' : 'pending',
                'tanggal_invoice' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
        
        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
