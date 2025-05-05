<?php

namespace Tests\Browser\Register;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can register and receive verification email
     *
     * @return void
     */
    public function test_user_can_register_and_receive_verification_email()
    { 
        $this->browse(function (Browser $browser) { 
            $browser->visit('/register')->screenshot('register-page') 
                    ->type('input[name="name"]', 'Test User') 
                    ->type('input[name="email"]', 'testuser@example.com') 
                    ->type('input[name="phone"]', '08123456789') 
                    ->type('input[name="address"]', 'Jl. Testing 123') 
                    ->type('input[name="password"]', 'Password123!') 
                    ->type('input[name="password_confirmation"]', 'Password123!') 
                    ->scrollIntoView('button[type="submit"]')
                    ->press('Daftar') 
                    ->assertPathIs('/email/verify') 
                    ->assertSee('Registrasi berhasil dan silahkan cek email!'); // Pesan sukses setelah register
        }); 
    }

    /**
     * Test user cannot register with existing email
     *
     * @return void
     */
    public function test_user_cannot_register_with_existing_email()
    { 
        // Buat user dengan email yang sudah ada
        User::factory()->create([ 
            'name' => 'Existing User', 
            'email' => 'existing@example.com', 
            'password' => Hash::make('password123'), 
            'phone' => '08123456789',
            'address' => 'Jl. Existing 123',
        ]);

        $this->browse(function (Browser $browser) { 
            $browser->visit('/register')
                    ->type('input[name="name"]', 'Another User') 
                    ->type('input[name="email"]', 'existing@example.com') // Email yang sudah ada
                    ->type('input[name="phone"]', '08987654321') 
                    ->type('input[name="address"]', 'Jl. Another 456') 
                    ->type('input[name="password"]', 'Password123!') 
                    ->type('input[name="password_confirmation"]', 'Password123!') 
                    ->scrollIntoView('button[type="submit"]')
                    ->press('Daftar')
                    ->assertPathIs('/register')
                    ->assertSee('Email yang anda daftarkan sudah tersedia'); // Pesan error untuk email yang sudah ada
        }); 
    }
}