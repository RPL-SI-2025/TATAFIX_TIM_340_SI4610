<?php

namespace Tests\Browser\Payment_Validation;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Service;
use Spatie\Permission\Models\Role;

class PaymentValidationTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_customer_payment(): void
    {
        $this->browse(function (Browser $browser) {
            // use customer user
            $customer = \App\Models\User::find(2);

            // use tukang user
            $tukang = \App\Models\User::find(3);

            // use booking status
            $booking_status = BookingStatus::find(1);

            // use a service
            $service = \App\Models\Service::find(3);

            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'service_id' => $service->service_id,
                'assigned_worker_id' => $tukang->id,
                'status_id' => $booking_status->id,
                'alamat' => 'Test Address',
                'nama_pemesan' => 'Test Customer',
                'service_name' => 'Test Service',
                'tanggal_booking' => now()->format('Y-m-d'),
                'waktu_booking' => '09:00:00',
                'catatan_perbaikan' => 'Test Notes',
                'dp_amount' => 50000,
                'final_amount' => 100000,
            ]);

            $browser->loginAs($customer)
                    ->visit('/')
                    ->assertPathIs('/')
                    
                    // View booking details
                    ->visit('/payment/dp/' . $booking->id)
                    ->assertSee('Pembayaran DP Booking')
                    ->attach('input[type="file"]', base_path('tests/Browser/test-image.jpeg'))
                    ->press('button[type="submit"');
                    
        });
    }
}
