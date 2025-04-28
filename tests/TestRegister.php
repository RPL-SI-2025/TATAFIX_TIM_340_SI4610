<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User; // Pastikan model User sudah diimport
use Illuminate\Support\Facades\Hash;

class RegistrasiTest extends DuskTestCase
{
    // use DatabaseMigrations;
    /** 
    * @group daftarakun
    * A Dusk test example.
    */
    public function user_can_register_and_receive_verification_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')->screenshot('register-page')
                    ->type('input[name="name"]', 'Test User')
                    ->type('input[name="email"]', 'testuser@example.com')
                    ->type('input[name="phone"]', '08123456789')
                    ->type('input[name="address"]', 'Jl. Testing 123')
                    ->type('input[name="password"]', 'Password123!')
                    ->type('input[name="password_confirmation"]', 'Password123!')
                    ->press('Daftar Sekarang')
                    ->assertPathIs('/email/verify')
                    ->assertSee('email sudah dikirim di email'); // Ganti dengan message sukses setelah register
        });
    }


    /** @test */
    public function user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
        ]);

    
    }
}

