<?php

namespace Tests\Browser\UpdateStatus;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Service;
use Spatie\Permission\Models\Role;

class AdminUpdateStatusFailTest extends DuskTestCase
{
    /**
     * Test admin fails to delete booking without reason.
     */
    public function testAdminFailsToDeleteBookingWithoutReason(): void
    {
        
        $this->browse(function (Browser $browser) {
            // use admin user
            $admin = \App\Models\User::find(1);

            // use customer user
            $customer = \App\Models\User::find(2);

            // use tukang user
            $tukang = \App\Models\User::find(3);

            // use booking status
            $inProgressStatus = BookingStatus::firstOrCreate(
                ['status_code' => 'IN_PROGRESS'],
                ['status_name' => 'In Progress']
            );

            // use a service
            $service = \App\Models\Service::find(3);

            // Create a booking with IN_PROGRESS status
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'service_id' => $service->service_id,
                'assigned_worker_id' => $tukang->id,
                'status_id' => $inProgressStatus->id,
                'alamat' => 'Test Address',
                'nama_pemesan' => 'Test Customer',
                'service_name' => 'Test Service',
                'tanggal_booking' => now()->format('Y-m-d'),
                'waktu_booking' => '09:00:00',
                'catatan_perbaikan' => 'Test Notes',
                'dp_amount' => 50000,
                'final_amount' => 100000,
            ]);

            // Test admin login and navigation to booking detail
            $browser->loginAs($admin)
                    ->visit('/')
                    ->assertPathIs('/')
                    
                    // View booking details
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Klik tombol Hapus Booking untuk membuka modal
                    ->press('Hapus Booking')
                    
                    // Verifikasi modal muncul dengan field alasan penghapusan
                    ->waitForText('Alasan Penghapusan')
                    ->assertSee('Alasan Penghapusan')
                    
                    // Verifikasi bahwa form validasi HTML bekerja
                    // Kita hanya memeriksa bahwa field alasan penghapusan memiliki atribut required
                    ->assertAttribute('#delete_reason', 'required', 'true');
        });
    }
}