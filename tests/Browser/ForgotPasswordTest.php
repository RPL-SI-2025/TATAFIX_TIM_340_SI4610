<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ForgotPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    #[Test]
    public function test_user_can_see_forgot_password_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password') // sesuaikan dengan route kamu
                    ->assertSee('Lupa Password?')
                    ->assertVisible('input[name=email]')
                    ->assertVisible('button[type=submit]');
        });
    }

    #[Test]
    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create([
            'email' => 'ratu@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/forgot-password')
                    ->type('email', $user->email)
                    ->press('Kirim Link Reset Password')
                    ->pause(1000)
                    ->assertSee('Link reset password sudah dikirim ke email kamu!');
        });
    }

    #[Test]
    public function test_error_message_appears_for_invalid_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password')
                    ->type('email', 'invalid-email@example.com')
                    ->press('Kirim Link Reset Password')
                    ->pause(1000)
                    ->assertSee("We can't find a user with that email address.");
        });
    }
}
