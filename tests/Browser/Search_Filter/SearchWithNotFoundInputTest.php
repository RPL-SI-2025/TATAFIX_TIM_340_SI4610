<?php

namespace Tests\Browser\Search_Filter;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchWithNotFoundInputTest extends DuskTestCase
{
    /**
     * Test search with not found input functionality
     *
     * @return void
     */
    public function testSearchWithNotFoundInput()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->type('input[name=search]', 'TidakAdaLayanan')
                ->press('Filter Layanan')
                ->screenshot('search-with-not-found-input')
                ->waitForText('Layanan tidak ditemukan')
                ->assertSee('Layanan tidak ditemukan');
        });
    }
}