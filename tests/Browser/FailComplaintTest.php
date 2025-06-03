<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;

class FailComplaintTest extends DuskTestCase
{
    #[Group('failcomplaint')]
    public function testCustomerCannotUploadInvalidFileType()
    {
        Storage::fake('public');

        $this->browse(function (Browser $browser) {
            // Login
            $browser->visit('/login')
                    ->waitFor('input[name="email"]', 10)
                    ->type('email', 'customer@tatafix.com')
                    ->type('password', 'customer123')
                    ->press('Login')
                    ->pause(3000);

            // Navigate to complaint form
            $browser->visitRoute('customer.complaints.create')
                    ->waitForText('Laporan Pengaduan', 10)
                    ->type('title', 'Lampiran Tidak Valid')
                    ->type('description', 'Saya mencoba mengunggah file PDF.')
                    ->pause(2000);

            // Scroll and attach PDF file
            $browser->driver->executeScript('window.scrollTo(0, document.body.scrollHeight);');
            $browser->pause(500);

            // Attach invalid file type
            $browser->attach('input[type="file"]', __DIR__.'/test-fail.pdf')
                    ->pause(3000);

            // Take screenshot for debugging
            $browser->screenshot('upload-invalid-file');

            // Submit the form
            $browser->press('button[type="submit"]')
                    ->pause(3000);

            // Assert that validation error appears
            $browser->assertSee('File harus berformat JPG atau PNG');
        });
    }
}
