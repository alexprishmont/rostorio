<?php

namespace App\Http\Controllers\Users\Employees;

use App\Actions\Address\CreateAddressAction;
use App\Actions\User\CreateUserAction;
use App\Actions\User\SetCompanyIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class EmployeesController extends Controller
{
    public function index(): Response
    {
        $users = User::with('roles')
            ->where('company_id', auth()->user()->company_id)
            ->get();

        return Inertia::render('Employees/Index', [
            'employees' => $users,
            'statuses' => function () use ($users): array {
                $userStatuses = [];
                $users->each(function (User $user) use (&$userStatuses): void {
                    $userStatuses[$user->id] = $user->getStatus();
                });

                return $userStatuses;
            },
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Employees/Create');
    }

    public function store(
        StoreEmployeeRequest $request,
        CreateUserAction $action,
        SetCompanyIdAction $companyIdAction,
        CreateAddressAction $addressAction
    ): RedirectResponse {
        $attributes = $request->validated();
        $address = Arr::only($attributes, ['phone']);
        $role = Arr::get($attributes, 'role');
        $attributes = Arr::only($attributes, ['firstname', 'lastname', 'email', 'password']);

        $user = $action->execute($attributes);

        if ($user) {
            $companyIdAction->execute($user, auth()->user()->company_id);
            $user->assignRole($role);
            $addressAction->execute($user, $address);

            return redirect()->route('employees.index')->with('flash', [
                'type' => 'success',
                'header' => __('app.success_action'),
                'text' => __('app.success_message'),
            ]);
        }

        return back()->with('flash', [
            'type' => 'error',
            'header' => __('app.error_action'),
            'text' => __('app.something_went_wrong'),
        ]);
    }

    public function show(User $employee): Response
    {
        $employee->load('roles');

        return Inertia::render('Employees/Show', [
            'employee' => $employee,
        ]);
    }
}
