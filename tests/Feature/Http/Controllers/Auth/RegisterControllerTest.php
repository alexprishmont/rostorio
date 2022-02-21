<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Events\Auth\UserRegistrationSucceeded;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $credentials = [
        'email' => 'test@example.com',
        'password' => '123123123',
        'name' => 'Larry Doe',
    ];

    public function test_it_returns_register_vue_component(): void
    {
        $this->from('/sign-up')
            ->get('/sign-up')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Auth/Register'));
    }

    public function test_it_returns_validations_error_if_email_already_exists(): void
    {
        User::factory()->create($this->credentials);

        $this->from('/sign-up')
            ->post('/sign-up', $this->credentials)
            ->assertSessionHasErrors(['email']);
    }

    public function test_it_creates_a_new_user_with_given_credentials(): void
    {
        $this->from('/sign-up')
            ->post('/sign-up', $this->credentials);

        $attributes = $this->credentials;
        unset($attributes['password']);

        $this->assertDatabaseHas('users', $attributes);
    }

    public function test_it_redirects_to_sign_in_page_with_success_message_if_user_created(): void
    {
        $this->from('/sign-up')
            ->post('/sign-up', $this->credentials)
            ->assertRedirect(route('dashboard.index'))
            ->assertSessionHas('message', ['success' => __('auth.registration_success')]);
    }

    public function test_it_dispatches_event_after_success_registration(): void
    {
        Event::fake();

        $this->from('/sign-up')
            ->post('/sign-up', $this->credentials);
        Event::assertDispatched(UserRegistrationSucceeded::class);
    }
}
