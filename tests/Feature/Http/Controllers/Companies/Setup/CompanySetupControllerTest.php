<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Companies\Setup;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanySetupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_it_returns_all_roles(): void
    {
        $response = $this->get(route('company.initialSetup.index'));
        $result = $response->getOriginalContent();

        $expected = Role::query()
            ->where('guard_name', 'company_'.$this->company->id)
            ->get()
            ->pluck('name')
            ->toArray();

        $this->assertEquals($expected, $result);
    }

    public function test_it_creates_company_with_given_name(): void
    {
        $this->from(route('profile.work.index'))
            ->post(route('company.initialSetup.store'), [
                'name' => 'Test company',
                'role' => 'Darbuotojas',
            ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Test company',
        ]);
    }

    public function test_it_assigns_user_to_created_company(): void
    {
        $this->from(route('profile.work.index'))
            ->post(route('company.initialSetup.store'), [
                'name' => 'Test company',
                'role' => 'Darbuotojas',
            ]);

        $company = Company::query()
            ->where('name', 'Test company')
            ->first();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'company_id' => $company->id,
            'firstname' => $this->user->firstname,
            'lastname' => $this->user->lastname,
        ]);
    }

    public function test_it_assigns_choosen_role_to_user(): void
    {
        $user = $this->createUser([
            'firstname' => 'Test',
            'lastname' => 'Test',
            'email' => 'test@test.com',
            'password' => '123123',
        ]);

        $this->actingAs($user);

        $this->from(route('profile.work.index'))
            ->post(route('company.initialSetup.store'), [
                'name' => 'Test company',
                'role' => 'Darbuotojas',
            ]);

        $company = Company::query()
            ->where('name', 'Test company')
            ->first();

        $role = $user->roles()
            ->where('name', 'Darbuotojas')
            ->where('guard_name', 'company_'.$company->id)
            ->first();

        $this->assertNotNull($role);
    }

    public function test_it_redirects_to_main_page(): void
    {
        $user = $this->createUser([
            'firstname' => 'Test',
            'lastname' => 'Test',
            'email' => 'test@test.com',
            'password' => '123123',
        ]);

        $this->actingAs($user);

        $this->from(route('profile.work.index'))
            ->post(route('company.initialSetup.store'), [
                'name' => 'Test company',
                'role' => 'Darbuotojas',
            ])
            ->assertRedirect(route('profile.work.index'))
            ->assertSessionHas(['flash']);
    }
}
