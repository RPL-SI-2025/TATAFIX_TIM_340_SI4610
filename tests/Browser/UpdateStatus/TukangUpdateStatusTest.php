<?php

namespace Tests\Browser\UpdateStatus;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;

class TukangUpdateStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * Test tukang updates booking status from "Waiting Worker Confirmation" to "In Progress".
     *
     * @return void
     */
    public function testTukangConfirmsBooking()
    {
        $this->browse(function (Browser $browser) {
            // Create a tukang user
            $tukang = User::factory()->create([
                'name' => 'Tukang Test',
                'email' => 'tukang@example.com',
                'password' => bcrypt('password'),
                'role' => 'tukang'
            ]);

            // Create a customer user
            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]);

            // Find the waiting worker confirmation status
            $waitingStatus = BookingStatus::where('code', 'WAITING_WORKER_CONFIRMATION')->first();
            
            // Create a booking with waiting worker confirmation status
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'tukang_id' => $tukang->id,
                'booking_status_id' => $waitingStatus->id,
                // Add other required fields as needed
            ]);

            // Login as tukang
            $browser->visit('/login')
                    ->type('email', 'tukang@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/tukang/dashboard')
                    
                    // Navigate to the booking details page
                    ->visit('/tukang/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Confirm the booking
                    ->press('Konfirmasi Booking')
                    ->waitForText('Status berhasil diperbarui')
                    
                    // Assert the status is now "In Progress"
                    ->assertSee('Sedang Dikerjakan');
                    
            // Verify in the database
            $this->assertEquals('IN_PROGRESS', Booking::find($booking->id)->bookingStatus->code);
        });
    }

    /**
     * Test tukang marks booking as completed.
     *
     * @return void
     */
    public function testTukangMarksBookingAsCompleted()
    {
        $this->browse(function (Browser $browser) {
            // Create a tukang user
            $tukang = User::factory()->create([
                'name' => 'Tukang Test',
                'email' => 'tukang@example.com',
                'password' => bcrypt('password'),
                'role' => 'tukang'
            ]);

            // Create a customer user
            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]);

            // Find the in progress status
            $inProgressStatus = BookingStatus::where('code', 'IN_PROGRESS')->first();
            
            // Create a booking with in progress status
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'tukang_id' => $tukang->id,
                'booking_status_id' => $inProgressStatus->id,
                // Add other required fields as needed
            ]);

            // Login as tukang
            $browser->visit('/login')
                    ->type('email', 'tukang@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/tukang/dashboard')
                    
                    // Navigate to the booking details page
                    ->visit('/tukang/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Mark as completed
                    ->press('Selesaikan Pekerjaan')
                    ->waitForText('Status berhasil diperbarui')
                    
                    // Assert the status is now "Done"
                    ->assertSee('Selesai');
                    
            // Verify in the database
            $this->assertEquals('DONE', Booking::find($booking->id)->bookingStatus->code);
        });
    }
}
