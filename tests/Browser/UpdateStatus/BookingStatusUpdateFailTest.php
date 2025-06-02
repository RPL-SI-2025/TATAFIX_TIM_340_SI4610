<?php

namespace Tests\Browser\UpdateStatus;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;

class BookingStatusUpdateFailTest extends DuskTestCase
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
     * Test tukang cannot update status of a booking that is not assigned to them.
     *
     * @return void
     */
    public function testTukangCannotUpdateUnassignedBooking()
    {
        $this->browse(function (Browser $browser) {
            // Create two tukang users
            $tukang1 = User::factory()->create([
                'name' => 'Tukang 1',
                'email' => 'tukang1@example.com',
                'password' => bcrypt('password'),
                'role' => 'tukang'
            ]);

            $tukang2 = User::factory()->create([
                'name' => 'Tukang 2',
                'email' => 'tukang2@example.com',
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
            
            // Create a booking assigned to tukang1
            $booking = Booking::factory()->create([
                'user_id' => $customer->id,
                'tukang_id' => $tukang1->id,
                'booking_status_id' => $waitingStatus->id,
                // Add other required fields as needed
            ]);

            // Login as tukang2
            $browser->visit('/login')
                    ->type('email', 'tukang2@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/tukang/dashboard')
                    
                    // Try to access the booking details page
                    ->visit('/tukang/bookings/' . $booking->id)
                    
                    // Should be redirected or see an error message
                    ->assertDontSee('Konfirmasi Booking')
                    ->assertSee('Tidak dapat mengakses booking ini');
        });
    }

    /**
     * Test customer cannot update booking status.
     *
     * @return void
     */
    public function testCustomerCannotUpdateBookingStatus()
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

            // Login as customer
            $browser->visit('/login')
                    ->type('email', 'customer@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/customer/dashboard')
                    
                    // Navigate to the booking details page
                    ->visit('/customer/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Should not see status update buttons
                    ->assertDontSee('Konfirmasi Booking')
                    ->assertDontSee('Selesaikan Pekerjaan');
        });
    }

    /**
     * Test tukang cannot update status to completed without going through in-progress first.
     *
     * @return void
     */
    public function testTukangCannotSkipStatusSteps()
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
                    
                    // Should only see Confirm button, not Complete button
                    ->assertSee('Konfirmasi Booking')
                    ->assertDontSee('Selesaikan Pekerjaan');
        });
    }
}