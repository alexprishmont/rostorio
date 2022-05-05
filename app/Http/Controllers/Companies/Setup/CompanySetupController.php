<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies\Setup;

use App\Actions\Company\CreateCompanyAction;
use App\Actions\User\SetCompanyIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\InitialSetupCompanyStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CompanySetupController extends Controller
{
    public function index(): array
    {
        return Role::query()
            ->where('guard_name', 'company_'.Auth::user()->company->id)
            ->get()
            ->pluck('name')
            ->toArray();
    }

    public function store(
        InitialSetupCompanyStoreRequest $request,
        CreateCompanyAction $companyAction,
        SetCompanyIdAction $idAction
    ): RedirectResponse {
        $attributes = $request->validated();
        $companyAttributes = collect($attributes)->only('name')->toArray();

        $company = $companyAction->execute($companyAttributes);
        $role = Role::query()
            ->where('name', $attributes['role'])
            ->where('guard_name', 'company_'.$company->id)
            ->first();

        $idAction->execute(auth()->user(), $company->id);

        if ($role && $company) {
            auth()->user()->assignRole($role);

            return redirect()->route('profile.work.index')
                ->with('flash', [
                    'type' => 'success',
                    'header' => __('app.success_action'),
                    'text' => 'Company information saved',
                ]);
        }

        return back()->with('flash', [
            'type' => 'error',
            'header' => __('app.something_went_wrong'),
            'text' => '',
        ]);
    }
}
