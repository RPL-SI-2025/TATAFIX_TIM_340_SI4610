<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ForgotPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_forgot_password_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password')
                    ->assertSee('Lupa Password?')
                    ->assertSee('Masukkan email Anda untuk permintaan reset kata sandi');
        });
    }

    /** @test */
    public function user_can_submit_forgot_password_form_with_valid_email()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/forgot-password')
                    ->type('email', $user->email) // Masukkan email pengguna
                    ->press('Kirim Link Reset Password') // Klik tombol kirim
                    ->assertPathIs('/forgot-password') // Pastikan masih di halaman yang sama
                    ->assertSee('Kami telah mengirimkan link reset password ke email Anda.'); // Pastikan pesan sukses muncul
        });
    }

    /** @test */
    public function user_receives_error_message_for_invalid_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password')
                    ->type('email', 'invalid-email@example.com') // Masukkan email yang tidak terdaftar
                    ->press('Kirim Link Reset Password')
                    ->assertPathIs('/forgot-password')
                    ->assertSee('Email yang Anda masukkan tidak terdaftar'); // Pesan error jika email tidak valid
        });
    }
}
