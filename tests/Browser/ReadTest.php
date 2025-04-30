<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReadTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group readtukang
     */
    public function test_read_tukang(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profiletukang')
                    ->assertSee('Master Data Tukang')
                    ->assertSee('FOTO')
                    ->assertSee('NAMA')
                    ->assertSee('DOMISILI')
                    ->assertSee('NO. HANDPHONE')
                    ->assertSee('EMAIL')
                    ->assertSee('AKSI')        
            ;
        });
    }
}
