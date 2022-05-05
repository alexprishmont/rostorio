<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_renders_company_index_page_with_props(): void
    {
        $this->get(route('company.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Index')
                ->has('company'));
    }

    public function test_it_redirects_back_on_update_if_no_attributes_provided(): void
    {
        $this->from(route('company.index'))
            ->put(route('company.update', ['company' => $this->company]), [])
            ->assertRedirect(route('company.index'));
    }

    public function test_it_updates_shift_times(): void
    {
        $this->from(route('company.index'))
            ->put(
                route('company.update', ['company' => $this->company]),
                [
                    'shifts_begins_at' => [
                        'hours' => '8',
                        'minutes' => '15',
                    ],
                    'shifts_ends_at' => [
                        'hours' => '18',
                        'minutes' => '15',
                    ],
                ]
            )
            ->assertRedirect(route('company.index'))
            ->assertSessionHas(['flash']);

        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
            'name' => $this->company->name,
            'shifts_begins_at' => Carbon::now()
                ->setHours(8)
                ->setMinutes(15)
                ->setSeconds(0)
                ->format('Y-m-d H:i:s'),
            'shifts_ends_at' => Carbon::now()
                ->setHours(18)
                ->setMinutes(15)
                ->setSeconds(0)
                ->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_it_updates_company_name(): void
    {
        $this->from(route('company.index'))
            ->put(
                route('company.update', ['company' => $this->company]),
                ['name' => 'Test company']
            )
            ->assertRedirect(route('company.index'))
            ->assertSessionHas(['flash']);

        $this->assertDatabaseHas('companies', [
            'id' => $this->company->id,
            'name' => 'Test company',
        ]);
    }
}
