<?php

namespace Tests;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected Company $company;
    protected User $user;

    public function signIn(): void
    {
        $this->company = Company::factory()->create();
        $this->user = $this->company->accounts()->create([
            'firstname' => 'Joe',
            'lastname' => 'Doe',
            'email' => 'test@example.com',
            'password' => '123123',
        ]);

        $role = Role::query()
            ->where('name', 'Darbuotojas')
            ->where('guard_name', 'company_'.$this->company->id)
            ->first();

        $this->user->syncRoles($role);

        $this->actingAs($this->user);
    }

    public function createUser(array $data): Model
    {
        return User::create($data);
    }
}
