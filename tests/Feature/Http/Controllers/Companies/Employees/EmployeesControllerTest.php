<?php

namespace Tests\Feature\Http\Controllers\Companies\Employees;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EmployeesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    public function test_it_renders_correct_index_component(): void
    {
        $this->from(route('company.index'))
            ->get(route('company.employees.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Employees/Index')
                ->hasAll(['employees', 'statuses']));
    }

    public function test_it_renders_correct_create_component(): void
    {
        $this->from(route('company.index'))
            ->get(route('company.employees.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Employees/Create')
                ->hasAll(['roles']));
    }

    public function test_it_renders_correct_show_component(): void
    {
        $this->from(route('company.employees.index'))
            ->get(route('company.employees.show', ['employee' => $this->user->id]))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Companies/Employees/Show')
                ->hasAll(['employee', 'totalWorkHours', 'roles']));
    }

    public function test_it_creates_new_user_under_company(): void
    {
        $this->post(route('company.employees.store'), [
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'name@surname.com',
            'role' => 'Darbuotojas',
            'phone' => '+37066696369',
        ]);

        $this->assertDatabaseHas('users', [
            'company_id' => $this->company->id,
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'name@surname.com',
        ]);

        $this->assertDatabaseHas('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => 2,
            'phone' => '+37066696369',
        ]);
    }

    public function test_it_updates_user_first_and_last_name(): void
    {
        $user = $this->company->accounts()->create([
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'name@surname.com',
            'password' => '123123',
        ]);

        $this->put(route('company.employees.update', ['employee' => $user->id]), [
            'fullname' => 'William Doe',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => $this->company->id,
            'firstname' => 'William',
            'lastname' => 'Doe',
            'email' => 'name@surname.com',
        ]);
    }

    public function test_it_updates_user_email(): void
    {
        $user = $this->company->accounts()->create([
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'name@surname.com',
            'password' => '123123',
        ]);

        $this->put(route('company.employees.update', ['employee' => $user->id]), [
            'email' => 'email@email.com',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => $this->company->id,
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'email@email.com',
        ]);
    }

    public function test_it_updates_user_address(): void
    {
        $user = $this->company->accounts()->create([
            'firstname' => 'Name',
            'lastname' => 'Surname',
            'email' => 'name@surname.com',
            'password' => '123123',
        ]);

        $user->address()->create();

        $this->assertDatabaseHas('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => $user->id,
            'country' => null,
            'city' => null,
            'street' => null,
            'house_number' => null,
            'zip' => null,
            'phone' => null,
        ]);

        $this->put(route('company.employees.update', ['employee' => $user->id]), [
            'address' => [
                'country' => 'Lithuania',
                'city' => 'Vilnius',
                'street' => 'Test str.',
                'zip' => '1234',
                'house_number' => '11',
                'phone' => '+37066696396',
            ],
        ]);

        $this->assertDatabaseHas('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => $user->id,
            'country' => 'Lithuania',
            'city' => 'Vilnius',
            'street' => 'Test str.',
            'zip' => '1234',
            'house_number' => '11',
            'phone' => '+37066696396',
        ]);
    }

    public function test_it_redirects_back_if_role_already_assigned(): void
    {
        $this->from(route('company.employees.show', ['employee' => $this->user->id]))
            ->post(
                route('company.employees.addRole', ['employee' => $this->user->id]),
                ['name' => 'Darbuotojas'],
            )
            ->assertRedirect(route('company.employees.show', ['employee' => $this->user->id]))
            ->assertSessionHasErrors(['already_has_role']);
    }

    public function test_it_assigns_correct_role_to_user(): void
    {
        $this->from(route('company.employees.show', ['employee' => $this->user]))
            ->post(
                route('company.employees.addRole', ['employee' => $this->user]),
                ['name' => 'Žmogiškųjų išteklių vadybininkas'],
            )
            ->assertSessionHas(['flash'])
            ->assertRedirect(route('company.employees.show', ['employee' => $this->user]));

        $userRoles = $this->user->roles()->get();
        $role = Role::query()
            ->where('name', 'Žmogiškųjų išteklių vadybininkas')
            ->where('guard_name', 'company_'.$this->company->id)
            ->first();

        $hasRole = false;

        $userRoles->each(function (Role $iterator) use (&$hasRole, $role): void {
            if ($iterator->is($role)) {
                $hasRole = true;

                return;
            }

            $hasRole = false;
        });

        $this->assertTrue($hasRole);
    }

    public function test_it_cannot_remove_last_role_from_user(): void
    {
        $role = $this->user->roles()->first();

        $this->from(route('company.employees.show', ['employee' => $this->user->id]))
            ->put(
                route('company.employees.removeRole', ['employee' => $this->user->id]),
                ['id' => $role->id],
            )
            ->assertSessionHasErrors(['last_role'])
            ->assertRedirect(route('company.employees.show', ['employee' => $this->user->id]));
    }

    public function test_it_removes_correct_role_from_user(): void
    {
        $role = Role::query()
            ->where('name', 'Žmogiškųjų išteklių vadybininkas')
            ->where('guard_name', 'company_'.$this->company->id)
            ->first();

        $this->user->assignRole($role);

        $role = $this->user->roles()->first();

        $this->from(route('company.employees.show', ['employee' => $this->user->id]))
            ->put(
                route('company.employees.removeRole', ['employee' => $this->user->id]),
                ['id' => $role->id],
            )
            ->assertSessionHas(['flash'])
            ->assertRedirect(route('company.employees.show', ['employee' => $this->user->id]));


        $hasRole = false;
        $userRoles = $this->user->roles()->get();
        $userRoles->each(function (Role $iterator) use (&$hasRole, $role): void {
            if ($iterator->is($role)) {
                $hasRole = true;

                return;
            }

            $hasRole = false;
        });

        $this->assertFalse($hasRole);
    }
}
