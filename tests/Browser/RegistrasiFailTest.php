<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

class RegistrasiFailTest extends DuskTestCase
{
    // Gunakan DatabaseMigrations jika ingin database selalu dimulai dari kosong setiap test
    // use DatabaseMigrations;

    #[Test]
    public function user_cannot_register_with_existing_email()
    {
        // Buat user dengan email yang sudah ada
        User::factory()->create([ 
            'name' => 'Existing User', 
            'email' => 'existing@example.com', 
            'password' => Hash::make('password123!'), 
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

    #[Test]
    public function user_cannot_register_with_invalid_password()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('input[name="name"]', 'Test User Invalid Password')
                ->type('input[name="email"]', 'testuserinvalid@example.com')
                ->type('input[name="phone"]', '08123456789')
                ->type('input[name="address"]', 'Jl. Testing 123')
                ->type('input[name="password"]', 'password123') // huruf kecil semua dan tanpa karakter khusus
                ->type('input[name="password_confirmation"]', 'password123')
                ->press('Daftar')
                ->pause(500)
                ->assertSee('Format kata sandi tidak valid.');
        });
    }
}
