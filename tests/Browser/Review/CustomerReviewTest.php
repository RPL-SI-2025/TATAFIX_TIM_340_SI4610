<?php

namespace Tests\Browser\Review;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Role;

class CustomerReviewTest extends DuskTestCase
{
    // Gunakan DatabaseMigrations jika ingin reset database setiap test
    // use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // Pastikan role sudah ada
        foreach (['admin', 'tukang', 'customer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    /**
     * Test customer dapat memberikan review setelah booking selesai.
     *
     * @return void
     */
    public function testCustomerCanSubmitReviewAfterCompletedBooking()
    {
        // Setup data test
        $this->setupTestData();

        $this->browse(function (Browser $browser) {
            // Login sebagai customer
            $customer = User::role('customer')->first();
            
            $browser->loginAs($customer)
                // Buka halaman tracking booking yang sudah selesai
                ->visit('/booking/' . $this->booking->id . '/tracking')
                // Verifikasi halaman menampilkan status completed
                ->assertSee('Selesai')
                // Verifikasi form review tersedia
                ->assertSee('Beri Ulasan untuk Layanan Ini')
                // Isi form review
                ->click('label[for="rating-4"]') // Pilih rating 4 bintang
                ->type('feedback', 'Pelayanan sangat memuaskan, tukang datang tepat waktu dan bekerja dengan profesional.')
                // Submit form review
                ->press('Kirim Ulasan')
                // Verifikasi notifikasi sukses muncul
                ->assertSee('Terima kasih atas ulasan Anda')
                // Verifikasi halaman menampilkan review yang sudah dikirim
                ->assertSee('Rating:')
                ->assertPresent('.text-warning') // Pastikan ada bintang berwarna kuning (rating)
                ->assertSee('Pelayanan sangat memuaskan');
        });
    }

    /**
     * Setup data test untuk booking yang sudah selesai
     */
    private function setupTestData()
    {
        // Buat user customer jika belum ada
        $customer = User::role('customer')->first();
        if (!$customer) {
            $customer = User::factory()->create([
                'name' => 'Test Customer',
                'email' => 'customer@example.com',
            ]);
            $customer->assignRole('customer');
        }

        // Buat user tukang jika belum ada
        $tukang = User::role('tukang')->first();
        if (!$tukang) {
            $tukang = User::factory()->create([
                'name' => 'Test Tukang',
                'email' => 'tukang@example.com',
            ]);
            $tukang->assignRole('tukang');
        }

        // Buat service jika belum ada
        $service = Service::first();
        if (!$service) {
            $service = Service::create([
                'service_id' => 'SRV001',
                'title_service' => 'Perbaikan AC',
                'description' => 'Layanan perbaikan AC segala merk',
                'price' => 150000,
                'status' => 'active',
            ]);
        }

        // Buat status booking 'completed' jika belum ada
        $completedStatus = BookingStatus::where('status_code', 'completed')->first();
        if (!$completedStatus) {
            $completedStatus = BookingStatus::create([
                'status_code' => 'completed',
                'status_name' => 'Selesai',
                'display_name' => 'Selesai',
                'description' => 'Booking telah selesai',
            ]);
        }

        // Buat booking dengan status completed
        $this->booking = Booking::create([
            'user_id' => $customer->id,
            'service_id' => $service->service_id,
            'nama_pemesan' => $customer->name,
            'service_name' => $service->title_service,
            'tanggal_booking' => now()->format('Y-m-d'),
            'waktu_booking' => now()->format('H:i:s'),
            'alamat'=>'telkom',
            'catatan_perbaikan' => 'AC tidak dingin',
            'status_id' => $completedStatus->id,
            'status_code' => 'completed',
            'dp_amount' => 50000,
            'final_amount' => 100000,
            'assigned_worker_id' => $tukang->id,
            'completed_at' => now(),
            'dp_paid_at' => now()->subDays(2),
            'final_paid_at' => now()->subDay(),
        ]);
    }
}
