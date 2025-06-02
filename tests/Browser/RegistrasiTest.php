<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class RegistrasiTest extends DuskTestCase
{
    // use DatabaseMigrations;

    #[Test]
    #[Group('daftarakun')]
    public function user_can_register_and_receive_verification_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')->screenshot('register-page')
                ->type('input[name="name"]', 'Test User')
                ->type('input[name="email"]', 'testuser123@example.com')
                ->type('input[name="phone"]', '08123456789')
                ->type('input[name="address"]', 'Jl. Testing 123')
                ->type('input[name="password"]', 'Password123!')
                ->type('input[name="password_confirmation"]', 'Password123!')
                ->scrollIntoView('button[type="submit"]')
                ->press('Daftar')
                ->assertPathIs('/email/verify')
                ->assertSee('Registrasi berhasil dan silahkan cek email!');
        });
    }
}
