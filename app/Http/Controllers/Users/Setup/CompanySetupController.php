<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\Setup;

use App\Actions\Company\CreateCompanyAction;
use App\Actions\User\SetCompanyIdAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\InitialSetupCompanyStoreRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class CompanySetupController extends Controller
{
    public function store(
        InitialSetupCompanyStoreRequest $request,
        CreateCompanyAction $companyAction,
        SetCompanyIdAction $idAction
    ): RedirectResponse {
        $attributes = $request->validated();
        $companyAttributes = collect($attributes)->only('name')->toArray();

        $company = $companyAction->execute($companyAttributes);
        $role = Role::query()->where('name', $attributes['role'])->first()
            ?? Role::create(['name' => $attributes['role']]);

        $idAction->execute(auth()->user(), $company->id);

        if ($role && $company) {
            auth()->user()->assignRole($role);

            return redirect()->route('dashboard.index')
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
