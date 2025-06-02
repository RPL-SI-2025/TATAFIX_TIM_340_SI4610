<?php

namespace Tests\Browser\UpdateStatus;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use Spatie\Permission\Models\Role;

class AdminUpdateStatusTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Make sure roles exist
        foreach (['admin', 'tukang', 'customer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    public function testAdminCompletesBookingAfterPayment()
    {
        $validatingStatus = BookingStatus::firstOrCreate(
            ['status_code' => 'VALIDATING_FINAL_PAYMENT'],
            ['display_name' => 'Validasi Pembayaran Akhir', 'color_code' => '#FFA500', 'requires_action' => true]
        );

        BookingStatus::firstOrCreate(
            ['status_code' => 'COMPLETED'],
            ['display_name' => 'Selesai', 'color_code' => '#00FF00', 'requires_action' => false]
        );

        $this->browse(function (Browser $browser) use ($validatingStatus) {
            $admin = User::where('email', 'admin@tatafix.com')->firstOrFail();
            $customer = User::where('email', 'customer@tatafix.com')->firstOrFail();

            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'status_id' => $validatingStatus->id
            ]);

            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123') // sesuaikan jika password berbeda
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    ->press('Validasi Pembayaran')
                    ->waitForText('Pembayaran berhasil divalidasi')
                    ->assertSee('Selesai');

            $this->assertEquals('COMPLETED', Booking::find($booking->id)->bookingStatus->status_code);
        });
    }

    public function testAdminAssignsTukang()
    {
        $dpValidatedStatus = BookingStatus::firstOrCreate(
            ['status_code' => 'DP_VALIDATED'],
            ['display_name' => 'DP Tervalidasi', 'color_code' => '#FFA500', 'requires_action' => true]
        );

        BookingStatus::firstOrCreate(
            ['status_code' => 'WAITING_WORKER_CONFIRMATION'],
            ['display_name' => 'Menunggu Konfirmasi Tukang', 'color_code' => '#0000FF', 'requires_action' => true]
        );

        $this->browse(function (Browser $browser) use ($dpValidatedStatus) {
            $admin = User::where('email', 'admin@tatafix.com')->firstOrFail();
            $tukang = User::where('email', 'tukang@tatafix.com')->firstOrFail();
            $customer = User::where('email', 'customer@tatafix.com')->firstOrFail();

            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'booking_status_id' => $dpValidatedStatus->id,
            ]);

            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123') // sesuaikan jika password berbeda
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    ->select('tukang_id', $tukang->id)
                    ->press('Assign Tukang')
                    ->waitForText('Tukang berhasil ditugaskan')
                    ->assertSee('Menunggu Konfirmasi Tukang');

            $this->assertEquals('WAITING_WORKER_CONFIRMATION', Booking::find($booking->id)->bookingStatus->status_code);
            $this->assertEquals($tukang->id, Booking::find($booking->id)->assigned_worker_id);
        });
    }
}
