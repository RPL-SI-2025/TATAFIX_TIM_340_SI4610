<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Booking;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class BookingTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can view booking form.
     *
     * @return void
     */
    public function testUserCanViewBookingForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                    ->assertSee('Booking Layanan')
                    ->assertSee('Nama Pemesan')
                    ->assertSee('Alamat')
                    ->assertSee('No. Handphone')
                    ->assertSee('Tanggal Booking')
                    ->assertSee('Waktu Booking')
                    ->assertSee('Catatan Perbaikan');
        });
    }

    /**
     * Test user can submit a booking form.
     *
     * @return void
     */
    public function testUserCanSubmitBookingForm()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        $this->browse(function (Browser $browser) use ($tomorrow) {
            $browser->visit('/booking')
                    ->type('nama_pemesan', 'John Doe')
                    ->type('alamat', 'Jl. Testing No. 123, Jakarta')
                    ->type('no_handphone', '081234567890')
                    ->type('tanggal_booking', $tomorrow)
                    ->type('waktu_booking', '14:00')
                    ->type('catatan_perbaikan', 'AC tidak dingin, mohon diperbaiki')
                    ->press('Kirim Booking')
                    ->assertPathIs('/booking')
                    ->assertSee('Booking berhasil disimpan!');
            
            // Verify data is in database
            $this->assertDatabaseHas('bookings', [
                'nama_pemesan' => 'John Doe',
                'alamat' => 'Jl. Testing No. 123, Jakarta',
                'no_handphone' => '081234567890',
                'tanggal_booking' => $tomorrow,
                'catatan_perbaikan' => 'AC tidak dingin, mohon diperbaiki'
            ]);
        });
    }

    /**
     * Test form validation errors are displayed.
     *
     * @return void
     */
    public function testValidationErrorsAreDisplayed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                    ->press('Kirim Booking')
                    ->assertSee('nama pemesan field is required')
                    ->assertSee('alamat field is required')
                    ->assertSee('no handphone field is required')
                    ->assertSee('tanggal booking field is required')
                    ->assertSee('waktu booking field is required')
                    ->assertSee('catatan perbaikan field is required');
        });
    }

    /**
     * Test booking date must be in the future.
     *
     * @return void
     */
    public function testBookingDateMustBeInFuture()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $this->browse(function (Browser $browser) use ($today) {
            $browser->visit('/booking')
                    ->type('nama_pemesan', 'Jane Doe')
                    ->type('alamat', 'Jl. Testing No. 456, Jakarta')
                    ->type('no_handphone', '089876543210')
                    ->type('tanggal_booking', $today)
                    ->type('waktu_booking', '10:00')
                    ->type('catatan_perbaikan', 'Perbaikan pintu')
                    ->press('Kirim Booking')
                    ->assertSee('tanggal booking must be a date after today');
        });
    }
}