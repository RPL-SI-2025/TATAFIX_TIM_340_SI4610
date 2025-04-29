<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group updatetukang
     */
    public function test_update_tukang(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profiletukang/3/edit')
                    ->assertSee('Edit Profil Tukang')
                    ->type('name', 'Tukang Update')
                    ->type('address', 'Jakarta Updated')
                    ->type('phone', '086289754321')
                    ->press('Update');
        });
    }
}