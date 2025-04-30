<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group createtukang
     */
    public function test_create_tukang(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profiletukang')
                    ->assertSee('Master Data Tukang')
                    ->clickLink('Tambah')
                    ->assertSee('Tambah Tukang')
                    ->type('name', 'Tukang Test')
                    ->type('address', 'Jakarta')
                    ->type('phone', '08123456789')
                    ->type('email', 'tukangtest@gmail.com')
                    ->press('Simpan')           
            ;
        });
    }
}
