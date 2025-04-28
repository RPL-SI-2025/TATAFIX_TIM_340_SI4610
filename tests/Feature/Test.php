<?php

namespace Tests\Feature;

use Tests\TestCase;

class Test extends TestCase
{
    /**
     * Test mengakses halaman beranda.
     *
     * @return void
     */
    public function test_can_access_home_page()
    {
        $response = $this->get('/profiletukang');
        $response->assertStatus(200);
        $response->assertSee('Master Data Tukang');
    }

    /**
     * Test halaman tidak ditemukan (404).
     *
     * @return void
     */
    public function test_page_not_found()
    {
        $response = $this->get('/halamanyanggaktau');
        $response->assertStatus(404);
    }

    /**
     * Test method POST ke URL yang tidak ada (harusnya 405 Method Not Allowed atau 404).
     *
     * @return void
     */
    public function test_post_method_not_allowed()
    {
        $response = $this->post('/profiletukang');
        $response->assertStatus(405); // 405 Method Not Allowed
    }

    /**
     * Test content type HTML.
     *
     * @return void
     */
    public function test_home_page_returns_html()
    {
        $response = $this->get('/profiletukang');
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }
}
