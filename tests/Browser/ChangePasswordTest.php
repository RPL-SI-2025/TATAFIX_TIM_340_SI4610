<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChangePasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_change_password_page()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Login sebagai pengguna yang sudah ada
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->assertSee('Ganti Password')
                    ->assertSee('Kata Sandi Lama')
                    ->assertSee('Kata Sandi Baru')
                    ->assertSee('Konfirmasi Kata Sandi Baru');
        });
    }

    /** @test */
    public function user_can_change_password_with_valid_data()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Login sebagai pengguna yang sudah ada
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'oldpassword') // Masukkan kata sandi lama
                    ->type('new_password', 'newpassword') // Masukkan kata sandi baru
                    ->type('new_password_confirmation', 'newpassword') // Konfirmasi kata sandi baru
                    ->press('Ganti Password')
                    ->assertSee('Password updated successfully') // Pastikan pesan sukses muncul
                    ->assertPathIs('/profile'); // Redirect ke halaman profile setelah sukses
        });
    }

    /** @test */
    public function user_receives_error_when_passwords_do_not_match()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Login sebagai pengguna yang sudah ada
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'oldpassword') // Masukkan kata sandi lama
                    ->type('new_password', 'newpassword') // Masukkan kata sandi baru
                    ->type('new_password_confirmation', 'differentpassword') // Masukkan konfirmasi kata sandi yang berbeda
                    ->press('Ganti Password')
                    ->assertSee('The password confirmation does not match.') // Pastikan error muncul
                    ->assertPathIs(route('profile.change-password')); // Tetap di halaman change password
        });
    }

    /** @test */
    public function user_receives_error_when_current_password_is_incorrect()
    {
        // Membuat pengguna untuk uji coba
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Login sebagai pengguna yang sudah ada
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'wrongpassword') // Masukkan kata sandi lama yang salah
                    ->type('new_password', 'newpassword') // Masukkan kata sandi baru
                    ->type('new_password_confirmation', 'newpassword') // Konfirmasi kata sandi baru
                    ->press('Ganti Password')
                    ->assertSee('The current password is incorrect.') // Pastikan error muncul
                    ->assertPathIs(route('profile.change-password')); // Tetap di halaman change password
        });
    }
}
