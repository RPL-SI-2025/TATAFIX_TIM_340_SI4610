<?php

namespace Tests\Browser\Search_Filter;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchByServiceNameTest extends DuskTestCase
{
    /**
     * Test search by service name functionality
     *
     * @return void
     */
    public function testSearchByServiceName()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->pause(3000) // Tunggu lebih lama
                ->waitFor('input[name=search]')
                ->type('input[name=search]', 'Kulkas')
                ->press('Filter Layanan');
            $browser->screenshot('debug-booking-search');
            $browser->waitForText('Perbaikan Kulkas')
                ->assertSee('Perbaikan Kulkas');
        });
    }
}