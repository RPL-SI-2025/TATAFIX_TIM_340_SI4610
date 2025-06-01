<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;

class ComplaintTest extends DuskTestCase
{
    #[Group('complaint')]
    public function testCustomerCanCreateComplaint()
    {
        Storage::fake('public');

        $this->browse(function (Browser $browser) {
            // Login
            $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->pause(5000);

            // Navigate to complaint form and fill it out
            $browser->visitRoute('customer.complaints.create')
                    ->waitForText('Laporan Pengaduan', 10)
                    ->type('title', 'Jalan Rusak Parah')
                    ->type('description', 'Jalan di depan rumah saya rusak dan berlubang.')
                    ->pause(5000);
            
            // Scroll down and attach file
            $browser->driver->executeScript('window.scrollTo(0, document.body.scrollHeight);');
            $browser->pause(500);
            
            // Attach file
            $browser->attach('input[type="file"]', __DIR__.'/test-image.jpeg')
                    ->pause(5000);
            
            // Take a screenshot before submitting
            $browser->screenshot('before-submit');
            
            // Submit the form using a more reliable selector
            $browser->press('button[type="submit"]')
                    ->waitForLocation('/customer/complaints/success')
                    ->assertSee('Pengaduan Anda telah berhasil dikirim');
        });
    }
}
