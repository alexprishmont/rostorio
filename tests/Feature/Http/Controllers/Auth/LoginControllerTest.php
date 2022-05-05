<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_login_form_on_index_page(): void
    {
        $this->get(route('login'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Auth/Login'));
    }

    public function test_it_redirects_to_dashboard_if_credentials_are_correct(): void
    {
        User::factory()->create(['email' => 'test@example.com', 'password' => '123123']);
        $this->from(route('login'))
            ->post(route('login'), [
                'email' => 'test@example.com',
                'password' => '123123',
            ])
            ->assertRedirect('http://localhost/profile/work');
    }

    public function test_it_redirects_back_if_credentials_are_wrong(): void
    {
        $this->from(route('login'))
            ->post(route('login'), [
                'email' => 'test@email.com',
                'password' => '123123',
            ])
            ->assertRedirect('http://localhost/sign-in');
    }

    public function test_it_returns_error_if_credentials_are_wrong(): void
    {
        $this->from(route('login'))
            ->post(route('login'), [
                'email' => 'test@email.com',
                'password' => '123123',
            ])
            ->assertSessionHasErrors(['email']);
    }
}
