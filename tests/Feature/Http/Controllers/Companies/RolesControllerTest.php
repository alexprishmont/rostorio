<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_renders_correct_index_page_component_with_props(): void
    {
        $this->get(route('company.roles.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Roles/Index')
                ->has('roles'));
    }

    public function test_it_stores_roles_data_and_redirects_back_with_flash(): void
    {
        $companyRoles = Role::query()
            ->where('guard_name', 'company_'.$this->company->id)
            ->get()
            ->toArray();

        $role = reset($companyRoles);

        $this->from(route('company.roles.index'))
            ->post(route('company.roles.store'), [
                ['id' => $role['id'], 'name' => 'Test role'],
            ])
            ->assertRedirect(route('company.roles.index'))
            ->assertSessionHas(['flash']);

        $this->assertDatabaseHas('roles', [
            'id' => $role['id'],
            'name' => 'Test role',
            'guard_name' => 'company_'.$this->company->id,
        ]);
    }
}
