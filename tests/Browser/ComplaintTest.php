<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;

class ComplaintTest extends DuskTestCase
{
    /**
     * @group complaint
     */
public function testCustomerCanCreateComplaint()
{
    Storage::fake('public');

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->screenshot('debug-login') // Biar tahu errornya dari mana
                ->assertInputPresent('email') // Lebih akurat dari 'assertSee'
                ->type('email', 'customer@tatafix.com')
                ->type('password', 'customer123')
                ->press('Login')
                ->visitRoute('customer.complaints.create')
                ->assertSee('Laporan Pengaduan')
                ->waitFor('#title')
                ->type('#title', 'Jalan Rusak Parah')
                ->pause(1000)
                ->waitFor('#description')
                ->type('textarea[name="description"]', 'Jalan di depan rumah saya rusak dan berlubang.')
                ->attach('attachment', __DIR__.'/files/sample-image.jpg')
                ->screenshot('debug-before-upload')
                ->press('Upload Pengaduan')
                ->pause(2000)
                ->screenshot('debug-after-complaint-submit')
                ->assertSee('Pengaduan Anda telah berhasil dikirim.')
                ->screenshot('complaint-submitted');
    });
}
}
