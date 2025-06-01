<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResetPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_reset_password_page_with_valid_token()
    {
        // Simulasi pembuatan user untuk reset password
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        // Membuat token reset password untuk pengguna
        $token = \Str::random(60); // Token acak, kamu bisa menggunakan token asli jika perlu
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        // Tes mengunjungi halaman reset password dengan token
        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                    ->assertSee('Ubah Kata Sandi')
                    ->assertSee('Masukkan kata sandi baru Anda');
        });
    }

    /** @test */
    public function user_can_reset_password_with_valid_data()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Membuat token reset password untuk pengguna
        $token = \Str::random(60); // Token acak, kamu bisa menggunakan token asli jika perlu
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        $this->browse(function (Browser $browser) use ($url, $user, $token) {
            // Mengunjungi halaman reset password
            $browser->visit($url)
                    ->type('password', 'newpassword') // Masukkan kata sandi baru
                    ->type('password_confirmation', 'newpassword') // Konfirmasi kata sandi baru
                    ->press('Atur Kata Sandi') // Klik tombol reset
                    ->assertSee('Kata sandi Anda telah berhasil diubah') // Pastikan pesan sukses muncul
                    ->assertPathIs('/login'); // Setelah reset password, redirect ke halaman login
        });
    }

    /** @test */
    public function user_receives_error_if_passwords_do_not_match()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Membuat token reset password untuk pengguna
        $token = \Str::random(60); // Token acak, kamu bisa menggunakan token asli jika perlu
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        $this->browse(function (Browser $browser) use ($url, $user, $token) {
            // Mengunjungi halaman reset password
            $browser->visit($url)
                    ->type('password', 'newpassword') // Masukkan kata sandi baru
                    ->type('password_confirmation', 'differentpassword') // Masukkan konfirmasi kata sandi yang berbeda
                    ->press('Atur Kata Sandi') // Klik tombol reset
                    ->assertSee('The password confirmation does not match.') // Pastikan error muncul
                    ->assertPathIs($url); // Tetap di halaman reset password
        });
    }
}
