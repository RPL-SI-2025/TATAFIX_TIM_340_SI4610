<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChangePasswordTest extends DuskTestCase
{
    /** @test */
    public function user_can_change_password_with_valid_data()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'oldpassword123')
                    ->type('password', 'newpassword456')
                    ->type('password_confirmation', 'newpassword456')
                    ->press('Ganti Password');
                    
        });
    }

    /** @test */
    public function user_cannot_change_password_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'wrongpassword')
                    ->type('password', 'newpassword456')
                    ->type('password_confirmation', 'newpassword456')
                    ->press('Ganti Password')
                    ->waitForText('Password saat ini tidak sesuai')
                    ->assertSee('Password saat ini tidak sesuai');
        });
    }

    /** @test */
    public function user_cannot_change_password_with_mismatched_confirmation()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('profile.change-password'))
                    ->type('current_password', 'oldpassword123')
                    ->type('password', 'newpassword456')
                    ->type('password_confirmation', 'differentpassword')
                    ->press('Ganti Password')
                    ->waitForText('The password field confirmation does not match') 
                    ->assertSee('The password field confirmation does not match');
        });
    }
}
