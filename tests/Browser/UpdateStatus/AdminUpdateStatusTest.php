<?php

namespace Tests\Browser\UpdateStatus;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;

class AdminUpdateStatusTest extends DuskTestCase
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
     * Test admin updates booking status to "Completed" after final payment validation.
     *
     * @return void
     */
    public function testAdminCompletesBookingAfterPayment()
    {
        $this->browse(function (Browser $browser) {
            // Create an admin user
            $admin = User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);

            // Create a customer user
            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]);

            // Find the validating final payment status
            $validatingStatus = BookingStatus::where('code', 'VALIDATING_FINAL_PAYMENT')->first();
            
            // Create a booking with validating final payment status
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'booking_status_id' => $validatingStatus->id,
                // Add other required fields as needed
            ]);

            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    
                    // Navigate to the booking details page
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Validate final payment
                    ->press('Validasi Pembayaran')
                    ->waitForText('Pembayaran berhasil divalidasi')
                    
                    // Assert the status is now "Completed"
                    ->assertSee('Selesai');
                    
            // Verify in the database
            $this->assertEquals('COMPLETED', Booking::find($booking->id)->bookingStatus->code);
        });
    }

    /**
     * Test admin assigns tukang to booking.
     *
     * @return void
     */
    public function testAdminAssignsTukang()
    {
        $this->browse(function (Browser $browser) {
            // Create an admin user
            $admin = User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);

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

            // Find the dp validated status
            $dpValidatedStatus = BookingStatus::where('code', 'DP_VALIDATED')->first();
            
            // Create a booking with dp validated status
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'booking_status_id' => $dpValidatedStatus->id,
                // Add other required fields as needed
            ]);

            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    
                    // Navigate to the booking details page
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Assign tukang
                    ->select('tukang_id', $tukang->id)
                    ->press('Assign Tukang')
                    ->waitForText('Tukang berhasil ditugaskan')
                    
                    // Assert the status is now "Waiting Worker Confirmation"
                    ->assertSee('Menunggu Konfirmasi Tukang');
                    
            // Verify in the database
            $this->assertEquals('WAITING_WORKER_CONFIRMATION', Booking::find($booking->id)->bookingStatus->code);
            $this->assertEquals($tukang->id, Booking::find($booking->id)->tukang_id);
        });
    }
}
