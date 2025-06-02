<?php

namespace Tests\Browser\Notification;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class NotificationAdminTest extends DuskTestCase
{
    #[Group('notifadmintest')]
    #[Test]
    public function admin_can_view_notifications(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                ->type('email', 'admin@tatafix.com')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(2000)
                ->assertSee('Dashboard Admin');

            // Klik ikon notifikasi
            $browser->click('@notification-icon')
                ->pause(1000)
                ->assertSee('Notifikasi')
                ->assertPresent('@notification-list')
                ->assertSee('Lihat semua notifikasi');
        });
    }

    #[Group('notifadmintest')]
    #[Test]
    public function admin_can_mark_all_notifications_as_read(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Selamat Datang')
                ->type('email', 'admin@tatafix.com')
                ->type('password', 'admin123')
                ->press('Login')
                ->pause(2000)
                ->assertSee('Dashboard Admin');

            // Klik ikon notifikasi
            $browser->click('@notification-icon')
                ->pause(1000);

            // Klik tombol 'Tandai semua dibaca'
            $browser->click('@mark-all-read')
                ->pause(1500);

            // Verifikasi tidak ada notifikasi belum dibaca (misal item dengan class read di Blade)
            $browser->click('@notification-icon') // buka ulang dropdown
                ->pause(500)
                ->assertMissing('.notification-item:not(.read)');
        });
    }

    #[Group('notifadmintest')]
    #[Test]
    public function test_admin_can_delete_notification()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1) // admin id 1
                ->visit('/')
                ->click('@notification-icon')
                ->waitFor('@delete-notification-1', 5) // tunggu elemen muncul max 5 detik
                ->click('@delete-notification-1')
                ->pause(1000);

            // Assert notifikasi sudah hilang / pesan sukses muncul
            $browser->assertDontSee('Notifikasi Dummy');
        });
    }

    #[Group('notifadmintest')]
    #[Test]
    public function test_admin_can_view_all_notifications_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/')
                ->click('@notification-icon')
                ->waitFor('@view-all-notifications', 5)
                ->scrollIntoView('@view-all-notifications')
                ->click('@view-all-notifications')
                ->pause(1000)
                ->assertPathIs('/notifications');
        });
    }
}
