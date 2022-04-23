<?php

namespace App\Http\Controllers\Companies\Employees;

use App\Actions\Address\CreateAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\StoreEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class EmployeesController extends Controller
{
    public function index(): Response
    {
        $users = auth()->user()->company->accounts()->with('roles')->get();

        return Inertia::render('Companies/Employees/Index', [
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
        return Inertia::render('Companies/Employees/Create', [
            'roles' => Role::all()->pluck('name')->toArray(),
        ]);
    }

    public function store(
        StoreEmployeeRequest $request,
        CreateAddressAction $addressAction
    ): RedirectResponse {
        $attributes = $request->validated();
        $address = Arr::only($attributes, ['phone']);
        $role = Arr::get($attributes, 'role');
        $attributes = Arr::only($attributes, ['firstname', 'lastname', 'email', 'password']);

        $user = auth()->user()->company->accounts()->create(
            array_merge($attributes, [
                'password' => sprintf(
                    '%s%s',
                    strtolower($attributes['firstname']),
                    strtolower($attributes['lastname'])
                ),
            ])
        );

        if ($user) {
            $user->assignRole($role);
            $addressAction->execute($user, $address);

            return redirect()->route('company.employees.index')->with('flash', [
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
        $employee->load(['roles', 'shifts', 'company', 'address', 'shiftRequests']);
        $shiftTime = $employee->company->shifts_begins_at->diffInHours($employee->company->shifts_ends_at);
        $shiftsCount = $employee->shifts()->whereBetween(
            'starts_at',
            [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]
        )->count();

        return Inertia::render('Companies/Employees/Show', [
            'employee' => new EmployeeResource($employee),
            'totalWorkHours' => $shiftTime * $shiftsCount,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $attributes = $request->validated();

        $fullname = $attributes['fullname'];
        unset($attributes['fullname']);

        $fullname = explode(' ', $fullname);

        $attributes = array_merge($attributes, [
            'firstname' => $fullname[0],
            'lastname' => $fullname[1],
        ]);

        $addressAttributes = Arr::get($attributes, 'address');
        unset($attributes['address']);

        $employee->update($attributes);
        $employee->address->update($addressAttributes);

        return back()->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => 'Employee has ben updated.',
        ]);
    }
}
