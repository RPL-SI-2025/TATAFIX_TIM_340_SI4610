<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;

class ServiceSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); 
    }

    public function test_search_by_title_service()
    {
        $response = $this->get('/booking?search=AC');
        $response->assertStatus(200);
        $response->assertSee('Service AC');
    }

    public function test_filter_by_category()
    {
        $category = Category::where('name', 'Elektronik')->first();
        $response = $this->get('/booking?category_id=' . $category->category_id);
        $response->assertStatus(200);
        $response->assertSee('Service AC');
        $response->assertDontSee('Service Motor');
    }

    public function test_filter_by_min_price()
    {
        $response = $this->get('/booking?min_price=120000');
        $response->assertStatus(200);
        $response->assertSee('Service AC');
        $response->assertDontSee('Service Motor');
    }

    public function test_filter_by_max_price()
    {
        $response = $this->get('/booking?max_price=90000');
        $response->assertStatus(200);
        $response->assertSee('Service Motor');
        $response->assertDontSee('Service AC');
    }

    public function test_filter_by_rating()
    {
        $response = $this->get('/booking?rating=4.7');
        $response->assertStatus(200);
        $response->assertSee('Service Motor');
        $response->assertDontSee('Cleaning Service');
    }

    public function test_combined_search_and_filter()
    {
        $category = Category::where('name', 'Rumah Tangga')->first();
        $response = $this->get('/booking?search=Cleaning&category_id=' . $category->category_id . '&min_price=90000&max_price=110000&rating=4.5');
        $response->assertStatus(200);
        $response->assertSee('Cleaning Service');
        $response->assertDontSee('Service AC');
    }
}
