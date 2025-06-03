<?php

namespace Tests\Browser\UpdateStatus;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Service;
use Spatie\Permission\Models\Role;

class AdminUpdateStatusTest extends DuskTestCase
{
    protected $admin;
    protected $customer;
    protected $tukang;
    protected $service;
    
    public function setUp(): void
    {
        parent::setUp();

        // Make sure roles exist
        foreach (['admin', 'tukang', 'customer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        
        // Create test users if they don't exist
        $this->admin = User::firstOrCreate(
            ['email' => 'admin@tatafix.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now()
            ]
        );
        $this->admin->assignRole('admin');
        
        $this->customer = User::firstOrCreate(
            ['email' => 'customer@tatafix.com'],
            [
                'name' => 'Customer Test',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now()
            ]
        );
        $this->customer->assignRole('customer');
        
        $this->tukang = User::firstOrCreate(
            ['email' => 'tukang@tatafix.com'],
            [
                'name' => 'Tukang Test',
                'password' => bcrypt('tukang123'),
                'email_verified_at' => now()
            ]
        );
        $this->tukang->assignRole('tukang');
        
        // Create a test service
        $this->service = Service::firstOrCreate(
            ['title_service' => 'Test Service'],
            [
                'description' => 'Test service description',
                'base_price' => 100000,
                'category_id' => 1,
                'provider_id' => 1, // Menambahkan provider_id yang diperlukan
                'label_unit' => 'Layanan', // Menambahkan label_unit yang diperlukan
                'availbility' => 1,
                'rating_avg' => 4.5,
                'image_url' => 'test.jpg'
            ]
        );
        
        // Pastikan service_id tersedia
        $this->service->refresh();
    }
    
    /**
     * Test admin can update booking status from detail page
     */
    public function testAdminUpdatesBookingStatus()
    {
        // Create necessary booking statuses
        $inProgressStatus = BookingStatus::firstOrCreate(
            ['status_code' => 'IN_PROGRESS'],
            ['status_name' => 'Sedang Dikerjakan', 'color_code' => '#9400D3', 'requires_action' => true]
        );
        
        $doneStatus = BookingStatus::firstOrCreate(
            ['status_code' => 'DONE'],
            ['status_name' => 'Pekerjaan Selesai', 'color_code' => '#0000FF', 'requires_action' => true]
        );
        
        $this->browse(function (Browser $browser) use ($inProgressStatus, $doneStatus) {
            // Create a booking with IN_PROGRESS status
            $booking = Booking::factory()->create([
                'user_id' => $this->customer->id,
                'service_id' => $this->service->service_id, // Menggunakan service_id, bukan id
                'status_id' => $inProgressStatus->id,
                'status_code' => $inProgressStatus->status_code,
                'dp_amount' => 50000,
                'final_amount' => 150000,
                'assigned_worker_id' => $this->tukang->id,
                'nama_pemesan' => 'Customer Test',
                'service_name' => 'Test Service',
                'tanggal_booking' => now()->addDays(7),
                'waktu_booking' => '09:00:00',
                'catatan_perbaikan' => 'Test catatan perbaikan',
                'alamat' => 'Jl. Test No. 123, Jakarta' // Menambahkan alamat yang diperlukan
            ]);
            
            // Login as admin
            $browser->visit('/login')
                    ->type('email', 'admin@tatafix.com')
                    ->type('password', 'admin123')
                    ->press('Login')
                    ->assertPathIs('/')
                    
                    // Go to booking list page
                    ->visit('/admin/bookings')
                    ->assertSee('Manajemen Booking')
                    
                    // Go to booking detail page
                    ->visit('/admin/bookings/' . $booking->id)
                    ->assertSee('Detail Booking')
                    
                    // Go to edit page
                    ->visit('/admin/bookings/' . $booking->id . '/edit')
                    ->assertSee('Edit Booking')
                    
                    // Change status to DONE
                    ->select('status_id', $doneStatus->id)
                    ->press('Simpan Perubahan')
                    ->acceptDialog() // Menerima konfirmasi alert
                    
                    // Verify redirected back to detail page with success message
                    ->assertPathIs('/admin/bookings/' . $booking->id)
                    ->assertSee('Booking berhasil diperbarui');
            
        });
    }
}
