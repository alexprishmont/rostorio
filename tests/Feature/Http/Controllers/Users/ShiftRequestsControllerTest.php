<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShiftRequestsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_renders_proper_component_on_index_page(): void
    {
        $this->get(route('profile.work.requests.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Users/Work/Requests/Index'));
    }

    public function test_it_stores_user_request(): void
    {
        $this->from(route('profile.work.requests.index'))
            ->post(route('profile.work.requests.store'), [
                'request' => 'cant_work',
                'shift_at' => Carbon::now()->format('Y-m-d'),
            ])
            ->assertRedirect(route('profile.work.requests.index'))
            ->assertSessionHas(['flash']);

        $this->assertDatabaseHas('shift_requests', [
            'user_id' => $this->user->id,
            'request' => 'cant_work',
            'shift_at' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function test_it_removes_user_request(): void
    {
        $request = $this->user->shiftRequests()->create([
            'request' => 'cant_work',
            'shift_at' => Carbon::now()->format('Y-m-d'),
        ]);

        $this->from(route('profile.work.requests.index'))
            ->delete(route('profile.work.requests.destroy', ['request' => $request->id]))
            ->assertRedirect(route('profile.work.requests.index'))
            ->assertSessionHas(['flash']);

        $this->assertDatabaseMissing('shift_requests', [
            'user_id' => $this->user->id,
            'request' => 'cant_work',
            'shift_at' => Carbon::now()->format('Y-m-d'),
        ]);
    }
}
