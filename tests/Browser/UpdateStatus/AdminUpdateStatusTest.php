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
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        
        // Make sure roles exist
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }
        if (!Role::where('name', 'tukang')->exists()) {
            Role::create(['name' => 'tukang', 'guard_name' => 'web']);
        }
        if (!Role::where('name', 'customer')->exists()) {
            Role::create(['name' => 'customer', 'guard_name' => 'web']);
        }
    }

    /**
     * Test admin updates booking status to "Completed" after final payment validation.
     *
     * @return void
     */
    public function testAdminCompletesBookingAfterPayment()
    {
        // First, check if the required booking status exists
        $validatingStatus = BookingStatus::where('status_code', 'VALIDATING_FINAL_PAYMENT')->first();
        if (!$validatingStatus) {
            // Create the status if it doesn't exist
            $validatingStatus = BookingStatus::create([
                'status_code' => 'VALIDATING_FINAL_PAYMENT',
                'display_name' => 'Validasi Pembayaran Akhir',
                'color_code' => '#FFA500',
                'requires_action' => true
            ]);
        }
        
        // Also make sure COMPLETED status exists
        if (!BookingStatus::where('status_code', 'COMPLETED')->exists()) {
            BookingStatus::create([
                'status_code' => 'COMPLETED',
                'display_name' => 'Selesai',
                'color_code' => '#00FF00',
                'requires_action' => false
            ]);
        }
        
        $this->browse(function (Browser $browser) use ($validatingStatus) {
            // Create an admin user
            $admin = User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password')
            ]);
            $admin->assignRole('admin');

            // Create a customer user
            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password')
            ]);
            $customer->assignRole('customer');
            
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
            $this->assertEquals('COMPLETED', Booking::find($booking->id)->bookingStatus->status_code);
        });
    }

    /**
     * Test admin assigns tukang to booking.
     *
     * @return void
     */
    public function testAdminAssignsTukang()
    {
        // First, check if the required booking status exists
        $dpValidatedStatus = BookingStatus::where('status_code', 'DP_VALIDATED')->first();
        if (!$dpValidatedStatus) {
            // Create the status if it doesn't exist
            $dpValidatedStatus = BookingStatus::create([
                'status_code' => 'DP_VALIDATED',
                'display_name' => 'DP Tervalidasi',
                'color_code' => '#FFA500',
                'requires_action' => true
            ]);
        }
        
        // Also make sure WAITING_WORKER_CONFIRMATION status exists
        if (!BookingStatus::where('status_code', 'WAITING_WORKER_CONFIRMATION')->exists()) {
            BookingStatus::create([
                'status_code' => 'WAITING_WORKER_CONFIRMATION',
                'display_name' => 'Menunggu Konfirmasi Tukang',
                'color_code' => '#0000FF',
                'requires_action' => true
            ]);
        }
        
        $this->browse(function (Browser $browser) use ($dpValidatedStatus) {
            // Create an admin user
            $admin = User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@example.com',
                'password' => bcrypt('password')
            ]);
            $admin->assignRole('admin');

            // Create a tukang user
            $tukang = User::factory()->create([
                'name' => 'Tukang Test',
                'email' => 'tukang@example.com',
                'password' => bcrypt('password')
            ]);
            $tukang->assignRole('tukang');

            // Create a customer user
            $customer = User::factory()->create([
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => bcrypt('password')
            ]);
            $customer->assignRole('customer');
            
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
            $this->assertEquals('WAITING_WORKER_CONFIRMATION', Booking::find($booking->id)->bookingStatus->status_code);
            $this->assertEquals($tukang->id, Booking::find($booking->id)->assigned_worker_id);
        });
    }
}
