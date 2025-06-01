<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class AdminComplaintTest extends DuskTestCase
{
    #[Test]
    #[Group('admincomplain')]
    public function admin_can_validate_complaint_successfully()
    {
        $this->browse(function (Browser $browser) {
            // Step 1: Login sebagai admin
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                ->type('email', 'admin@tatafix.com')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(2000)
                ->assertSee('Dashboard Admin')
                ->clickLink('Dashboard Admin')
                ->pause(1000)
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Dashboard');

            // Step 2: Buka halaman daftar pengaduan
            $browser->clickLink('Pengaduan')
                ->pause(1000)
                ->assertSee('Daftar Pengaduan Customer');

            // Step 3: Klik tombol Detail pada pengaduan pertama
            $browser->clickLink('Detail')
                ->pause(1000)
                ->assertSee('Detail Pengaduan');

            // Step 4: Validasi pengaduan - pilih "valid" dan isi catatan
            $browser->radio('status', 'valid')
                ->type('admin_notes', 'Pengaduan telah diperiksa dan dinyatakan valid.')
                ->pause(500)
                ->press('Simpan Validasi')
                ->pause(1500)
                ->assertSee('Pengaduan berhasil divalidasi');
        });
    }
}
