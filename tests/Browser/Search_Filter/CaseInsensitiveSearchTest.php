<?php

namespace Tests\Browser\Search_Filter;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CaseInsensitiveSearchTest extends DuskTestCase
{
    /**
     * Test case insensitive search functionality
     *
     * @return void
     */
    public function testCaseInsensitiveSearch()
    {
        $this->browse(function (Browser $browser) {
            // Test with lowercase
            $browser->visit('/booking')
                ->type('input[name=search]', 'kulkas') // lowercase
                ->press('Filter Layanan')
                ->screenshot('case-insensitive-search-lowercase')
                ->waitForText('Perbaikan Kulkas')
                ->assertSee('Perbaikan Kulkas');
                
            // Test with uppercase
            $browser->visit('/booking')
                ->type('input[name=search]', 'KULKAS') // uppercase
                ->press('Filter Layanan')
                ->screenshot('case-insensitive-search-uppercase')
                ->waitForText('Perbaikan Kulkas')
                ->assertSee('Perbaikan Kulkas');
                
            // Test with mixed case
            $browser->visit('/booking')
                ->type('input[name=search]', 'KuLkAs') // mixed case
                ->press('Filter Layanan')
                ->screenshot('case-insensitive-search-mixedcase')
                ->waitForText('Perbaikan Kulkas')
                ->assertSee('Perbaikan Kulkas');
        });
    }
}