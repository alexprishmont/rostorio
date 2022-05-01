<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(): Response
    {
        $roles = Role::query()->where('guard_name', 'company_'.Auth::user()->company_id)->get();

        return Inertia::render('Companies/Roles/Index', [
            'roles' => RoleResource::collection($roles),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $roles = $request->all();

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['id' => $role['id']],
                ['name' => $role['name']]
            );
        }

        return back()->with('flash', [
            'type' => 'success',
            'title' => __('app.success_action'),
            'text' => 'Roles are updated.',
        ]);
    }
}
