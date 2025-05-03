<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EditprofileTest extends DuskTestCase
{
    /**
     * @group edit
     */
    public function testUserCanViewProfile(): void
    {
        // Create a test user or use an existing one
        $user = User::where('email', 'rafi@gmail.com')->first();
        
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'muhammad rafi',
                'email' => 'rafi@gmail.com',
                'password' => Hash::make('rafi123'),
                'role_id' => 2, // Assuming 2 is for customer role
            ]);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email) // pakai name input 'email'
                    ->type('password', 'rafi123') // pakai password yang benar
                    ->press('Login')
                    ->waitForLocation('/dashboard') // menunggu redirect ke dashboard
                    ->visit('/profile')
                    ->assertSee($user->name)
                    ->assertSee($user->email);
        });
    }
    
    /**
     * Test user can edit profile
     */
    public function testUserCanEditProfile(): void
    {
        // Use an existing user
        $user = User::where('email', 'rafi@gmail.com')->first();
        
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'muhammad rafi',
                'email' => 'rafi@gmail.com',
                'password' => Hash::make('rafi123'),
                'role_id' => 2,
            ]);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email) // ini diperbaiki juga
                    ->type('password', 'rafi123') // pakai password yang benar
                    ->press('Login')
                    ->waitForLocation('/dashboard') // menunggu redirect ke dashboard
                    ->visit('/profile/edit')
                    ->assertSee('Informasi Akun')
                    ->type('name', 'Updated Name ' . time())
                    ->type('phone', '081234567890')
                    // Uncomment jika ingin menguji upload gambar
                    // ->attach('profile_image', __DIR__.'/../../public/images/default-avatar.jpg')
                    ->press('Simpan')
                    ->assertSee('Perubahan ini telah mengupdate profil Anda');
        });
    }
}
