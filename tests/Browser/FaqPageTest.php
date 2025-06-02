<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FaqPageTest extends DuskTestCase
{
    /** @test */
    public function user_can_access_faq_page_and_see_all_questions()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/faq') // pastikan route ini benar
                    ->assertSee('(FAQ) Frequently Asked Questions')
                    ->assertSee('Apa itu TataFix?')
                    ->assertSee('Bagaimana saya bisa memilih penyedia layanan terbaik?')
                    ->assertSee('Apakah saya bisa berkomunikasi dengan penyedia layanan sebelum pekerjaan dimulai?')
                    ->assertSee('Apakah saya bisa memesan layanan untuk orang lain (misalnya, keluarga atau teman)?')
                    ->assertSee('Bagaimana sistem pembayaran di TataFix?')
                    ->assertSee('Bagaimana cara menghubungi layanan pelanggan TataFix?');
        });
    }

    /** @test */
public function user_can_expand_and_collapse_faq_items()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/faq')
                ->pause(500) // kasih waktu render
                ->scrollIntoView('details:nth-of-type(1) summary') // pastikan terlihat
                ->script("document.querySelector('details:nth-of-type(1) summary').click();");

        $browser->pause(500)
                ->assertVisible('details:nth-of-type(1) p')
                ->script("document.querySelector('details:nth-of-type(1) summary').click();");

        $browser->pause(500)
                ->assertMissing('details:nth-of-type(1)[open] p');
    });
}

}
