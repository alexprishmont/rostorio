<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(): Response
    {
        $roles = Role::query()->where('properties->company_id', Auth::user()->company_id)->get();
        $permissions = Permission::all();

        $permissionStatuses = [];

        $roles->each(function (Role $role) use (&$permissionStatuses, $permissions): void {
            $permissions->each(function (Permission $permission) use (&$permissionStatuses, $role): void {
                $roles = $permission->roles()->get()->pluck('id')->toArray();

                if (in_array($role->id, $roles)) {
                    $permissionStatuses[$role->id][$permission->id] = ['status' => true];

                    return;
                }

                $permissionStatuses[$role->id][$permission->id] = ['status' => false];
            });
        });

        return Inertia::render('Companies/Roles/Index', [
            'roles' => RoleResource::collection($roles),
            'permissions' => PermissionResource::collection($permissions),
            'permissionStatuses' => $permissionStatuses,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $permissions = Permission::query()
            ->whereIn('id', $attributes['permissions'])
            ->get();

        $role = Role::create([
            'name' => $attributes['name'],
            'properties' => json_encode(['company_id' => Auth::user()->company_id]),
        ]);

        $permissions->each(function (Permission $permission) use ($role): void {
            $permission->assignRole($role);
        });

        return redirect()->route('company.index')->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('app.success_message'),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $permissionStatuses = $request->validated()['permissions'];
        $permissions = Permission::whereIn('id', array_keys($permissionStatuses))->get();

        $permissions->each(function (Permission $permission) use ($permissionStatuses, $role): void {
            if ($permissionStatuses[$permission->id]['status']) {
                $permission->assignRole($role);
                $role->givePermissionTo($permission);

                return;
            }

            $permission->removeRole($role);
        });

        return redirect()->route('company.index')->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('app.success_message'),
        ]);
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('company.index')->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('app.success_message'),
        ]);
    }
}
