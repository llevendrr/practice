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
            ->assertSee('Home')
            ->assertSee('Catalog');
    }
}
