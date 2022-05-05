<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_flushes_sessions_on_logout(): void
    {
        $this->post(route('logout'));
        $this->assertGuest();
    }

    public function test_it_redirects_to_login_page_with_flash(): void
    {
        $this->post(route('logout'))
            ->assertSessionHas(['flash'])
            ->assertRedirect(route('login'));
    }
}
