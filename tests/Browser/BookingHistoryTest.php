<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\BookingStatus;
use App\Models\Booking;
use App\Models\BookingLog; // asumsi ada model untuk tracking status
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;

class BookingHistoryTest extends DuskTestCase
{
    use WithFaker;

    /** @test */
    public function user_can_see_booking_detail_and_tracking_status()
    {
        // 1. Buat user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Buat category
        $category = Category::create([
            'name' => 'Kategori Test',
        ]);

        // 3. Buat service
        $service = Service::create([
            'provider_id' => $user->id,
            'title_service' => 'Test Service',
            'description' => 'Deskripsi service test',
            'category_id' => $category->category_id,
            'base_price' => 100000,
            'label_unit' => 'unit',
            'availbility' => true,
            'rating_avg' => 4.5,
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        // 4. Buat booking status
        $bookingStatus = BookingStatus::create([
            'status_code' => 'pending',
            'display_name' => 'Menunggu Pembayaran DP',
        ]);

        // 5. Buat booking
        // 5. Buat booking (lengkapi kolom wajib)
$booking = Booking::create([
    'user_id' => $user->id,
    'service_id' => $service->service_id,
    'status_id' => $bookingStatus->id,
    'booking_date' => now(),
    'nama_pemesan' => $user->name,          // contoh pakai nama user
    'alamat' => 'Jl. Contoh No.123',
    'no_handphone' => '08123456789',
    'catatan_perbaikan' => 'Tidak ada catatan',
]);


        // 6. Buat booking log (tracking status)
        BookingLog::create([
            'booking_id' => $booking->id,
            'status' => 'pending',
            'description' => 'Booking dibuat, menunggu pembayaran DP',
            'created_at' => now(),
        ]);

        BookingLog::create([
            'booking_id' => $booking->id,
            'status' => 'confirmed',
            'description' => 'Pembayaran diterima, booking dikonfirmasi',
            'created_at' => now()->addHours(1),
        ]);

        // 7. Test user login, buka halaman booking history dan cek detail + tracking
        $this->browse(function (Browser $browser) use ($user, $service, $bookingStatus) {
            $browser->loginAs($user)
                ->visit('/booking-history') // ganti dengan route halaman booking history
                ->assertSee($service->title_service)         // cek detail booking
                ->assertSee($bookingStatus->display_name)    // cek status booking
                ->assertSee('Booking dibuat, menunggu pembayaran DP')   // cek tracking log 1
                ->assertSee('Pembayaran diterima, booking dikonfirmasi'); // cek tracking log 2
        });
    }
}
