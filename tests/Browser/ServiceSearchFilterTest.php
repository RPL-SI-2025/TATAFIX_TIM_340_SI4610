<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Foundation\Testing\WithFaker;

class ServiceSearchFilterTest extends DuskTestCase
{
    use WithFaker;

    /**
     * Normal Case: Search by service name
     */
    public function test_search_by_service_name()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->type('input[name=search]', 'AC')
                ->press('Filter Layanan');
            $browser->screenshot('debug-booking-search');
            $browser->waitForText('Service AC')
                ->assertSee('Service AC');
        });
    }

    /**
     * Normal Case: Filter by category
     */
    public function test_filter_by_category()
    {
        $category = Category::where('name', 'Elektronik')->first();
        $this->browse(function (Browser $browser) use ($category) {
            $browser->visit('/booking')
                ->select('select[name=category_id]', $category->category_id)
                ->press('Filter Layanan');
            $browser->screenshot('debug-booking-category');
            $browser->waitForText('Service AC')
                ->assertSee('Service AC');
        });
    }

    /**
     * Exception Case: Search with empty input
     */
    public function test_search_with_empty_input()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->type('input[name=search]', '')
                ->press('Filter Layanan');
            $browser->screenshot('debug-booking-empty');
            $browser->waitForText('Service AC')
                ->assertSee('Service AC')
                ->assertSee('Service Motor');
        });
    }

    /**
     * Exception Case: Search with not found input
     */
    public function test_search_with_not_found_input()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/booking')
                ->type('input[name=search]', 'TidakAdaLayanan')
                ->press('Filter Layanan');
            $browser->screenshot('debug-booking-notfound');
            $browser->waitForText('Layanan tidak ditemukan')
                ->assertSee('Layanan tidak ditemukan');
        });
    }
}
