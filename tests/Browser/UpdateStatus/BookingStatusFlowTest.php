<?php

namespace Tests\Browser\UpdateStatus;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;

class BookingStatusFlowTest extends DuskTestCase
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
     * Test the complete booking flow from assignment to completion.
     *
     * @return void
     */
    public function testCompleteBookingFlow()
    {
        $this->browse(function (Browser $browser) {
            // Create users
            $admin = User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);

            $tukang = User::factory()->create([
                'name' => 'Tukang Test',
                'email' => 'tukang@example.com',
                'password' => bcrypt('password'),
                'role' => 'tukang'
            ]);

            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]);

            // Find the waiting assignment status
            $waitingAssignmentStatus = BookingStatus::where('code', 'WAITING_ASSIGNMENT')->first();
            
            // Create a booking waiting for assignment
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'booking_status_id' => $waitingAssignmentStatus->id,
                // Add other required fields as needed
            ]);

            // Step 1: Admin assigns tukang
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
                    ->assertSee('Menunggu Konfirmasi Tukang')
                    ->logout();
                    
            // Verify in the database
            $this->assertEquals('WAITING_WORKER_CONFIRMATION', Booking::find($booking->id)->bookingStatus->code);
            
            // Step 2: Tukang confirms booking
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
                    ->assertSee('Sedang Dikerjakan')
                    
                    // Mark as completed
                    ->press('Selesaikan Pekerjaan')
                    ->waitForText('Status berhasil diperbarui')
                    
                    // Assert the status is now "Done"
                    ->assertSee('Selesai')
                    ->logout();
                    
            // Verify in the database
            $this->assertEquals('DONE', Booking::find($booking->id)->bookingStatus->code);
            
            // Step 3: Admin completes the booking after final payment
            // (Assuming customer has uploaded final payment and status is VALIDATING_FINAL_PAYMENT)
            
            // Update booking status to VALIDATING_FINAL_PAYMENT
            $validatingStatus = BookingStatus::where('code', 'VALIDATING_FINAL_PAYMENT')->first();
            $booking->booking_status_id = $validatingStatus->id;
            $booking->save();
            
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
}
