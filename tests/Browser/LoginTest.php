<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * @group masuk
     */
    public function testLoginPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login') // sesuaikan path kalau perlu
                    ->assertSee('TataFix')
                    ->assertSee('Email')
                    ->assertSee('Kata Sandi');
        });
    }

    /**
     * Test successful login.
     */
    public function testSuccessfulLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login_process.php')
                    ->type('email', 'user@example.com')    // isi dengan email yang ada di database
                    ->type('password', 'password')          // isi dengan password yang sesuai
                    ->press('Login')
                    ->assertPathIs('/home')                 // asumsi redirect ke /home setelah login
                    ->assertSee('Dashboard');              // asumsi ada tulisan "Dashboard" di halaman home
        });
    }

    /**
     * Test login with invalid credentials.
     */
    public function testLoginWithInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login_process.php')
                    ->type('email', 'salah@example.com')
                    ->type('password', 'passwordsalah')
                    ->press('Login')
                    ->assertSee('Email atau kata sandi salah'); // asumsi ada error message
        });
    }
}
