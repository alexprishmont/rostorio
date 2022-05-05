<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Shifts;

use App\Http\Resources\ShiftResource;
use App\Models\Shifts\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShiftsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();

        User::factory()->create(['company_id' => $this->company]);
        User::factory()->create(['company_id' => $this->company]);
        User::factory()->create(['company_id' => $this->company]);
    }

    public function test_it_index_redirects_to_company_page(): void
    {
        $this->get(route('shifts.index'))
            ->assertRedirect(route('company.index'));
    }

    public function test_it_renders_proper_component_on_create_page_with_props(): void
    {
        $this->get(route('shifts.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Shifts/Create')
                ->has('accounts'));
    }

    public function test_it_generates_schedule_for_selected_month(): void
    {
        $this->from(route('shifts.create'))
            ->get(route('shifts.generate', ['date' => Carbon::now()->format('Y-m-d')]));

        $this->assertDatabaseCount('shifts', Carbon::now()->daysInMonth);
    }

    public function test_it_returns_error_if_schedule_already_generated_for_selected_month(): void
    {
        $this->from(route('shifts.create'))
            ->get(route('shifts.generate', ['date' => Carbon::now()->format('Y-m-d')]));


        $this->from(route('shifts.create'))
            ->get(route('shifts.generate', ['date' => Carbon::now()->format('Y-m-d')]))
            ->assertRedirect(route('shifts.create'))
            ->assertSessionHasErrors(['already_generated']);
    }

    public function test_it_returns_shifts_by_date_for_company(): void
    {
        $this->from(route('shifts.create'))
            ->get(route('shifts.generate', ['date' => Carbon::now()->format('Y-m-d')]));

        $response = $this->get(route('shifts.getByDate', ['date' => Carbon::now()]));
        $result = json_decode($response->getContent(), true);

        $this->assertCount(Carbon::now()->daysInMonth, $result);
    }

    public function test_it_returns_shifts_by_date_for_user(): void
    {
        $this->from(route('shifts.create'))
            ->get(route('shifts.generate', ['date' => Carbon::now()->format('Y-m-d')]));

        $response = $this->get(route('shifts.getByDate', ['date' => Carbon::now(), 'userId' => $this->user->id]));
        $result = ShiftResource::collection($response->getOriginalContent());
        $shifts = ShiftResource::collection(Shift::query()
            ->with('worker.shiftRequests')
            ->where('user_id', $this->user->id)
            ->get());

        $this->assertEquals($shifts, $result);
    }
}
