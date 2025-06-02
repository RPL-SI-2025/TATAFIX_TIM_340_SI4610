<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Password;

class ResetPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_reset_password_page_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        // Gunakan token yang valid dari broker
        $token = Password::broker()->createToken($user);
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                    ->assertSee('Ubah Kata Sandi')
                    ->assertSee('Masukkan kata sandi baru Anda');
        });
    }

    /** @test */
    public function user_can_reset_password_with_valid_data()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        // Token valid
        $token = Password::broker()->createToken($user);
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                    ->type('password', 'newpassword')
                    ->type('password_confirmation', 'newpassword')
                    ->press('Atur Kata Sandi');   
        });
    }

    /** @test */
    public function user_receives_error_if_passwords_do_not_match()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $token = Password::broker()->createToken($user);
        $url = route('password.reset', ['email' => $user->email, 'token' => $token]);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                    ->type('password', 'newpassword')
                    ->type('password_confirmation', 'differentpassword')
                    ->press('Atur Kata Sandi')
                    ->assertSee('Konfirmasi kata sandi tidak cocok.')
                    ->assertPathIs('/reset-password/*'); // tetap di halaman yang sama
        });
    }
}
