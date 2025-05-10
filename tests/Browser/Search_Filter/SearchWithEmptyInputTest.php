<?php

namespace Tests\Browser\Search_Filter;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchWithEmptyInputTest extends DuskTestCase
{
    /**
     * Test search with empty input functionality
     *
     * @return void
     */
    public function testSearchWithEmptyInput()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->type('input[name=search]', '')
                ->press('Filter Layanan')
                ->screenshot('search-with-empty-input')
                ->waitForText('Perbaikan Kulkas') // Assuming this is a default service
                ->assertSee('Perbaikan Kulkas')
                ->assertSee('Perbaikan TV'); // Assuming this is another default service
        });
    }
}