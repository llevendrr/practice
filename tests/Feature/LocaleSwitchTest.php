<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    public function test_it_switches_to_english_and_persists_between_requests(): void
    {
        $response = $this->from('/')->get(route('locale.switch', ['locale' => 'en']));

        $response->assertRedirect('/');
        $response->assertCookie('locale', 'en');
        $this->assertSame('en', session('locale'));

        $this->get('/')
            ->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('lang-btn--active')
            ->assertSee('Home')
            ->assertSee('Catalog');
    }

    public function test_it_rejects_invalid_locale(): void
    {
        $this->get('/lang/de')->assertNotFound();
    }

    public function test_legacy_locale_url_redirects_to_new_route(): void
    {
        $this->get('/locale/en')->assertRedirect('/lang/en');
    }
}
