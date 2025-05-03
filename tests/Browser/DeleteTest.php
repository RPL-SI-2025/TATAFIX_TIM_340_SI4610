<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group deletetukang
     */
    public function test_delete_tukang(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profiletukang')
                    ->assertSee('Master Data Tukang')
                    ->assertSee('Tukang Test')  
                    ->press('.text-red-500')
                    ->acceptDialog();
        });
    }
}