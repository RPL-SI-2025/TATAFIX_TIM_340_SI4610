<?php

namespace Tests\Browser\Filter;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;

class FilterByCategoryTest extends DuskTestCase
{
    /**
     * Test filter by category functionality
     *
     * @return void
     */
    public function testFilterByCategory()
    {
        $category = Category::where('name', 'Elektronik')->first();
        
        $this->browse(function (Browser $browser) use ($category) {
            $browser->visit('/booking')
                ->select('select[name=category_id]', $category->category_id)
                ->press('Filter Layanan')
                ->screenshot('filter-by-category')
                ->waitForText('Perbaikan Kulkas')
                ->assertSee('Perbaikan Kulkas');
        });
    }
    
}