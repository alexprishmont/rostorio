<?php

declare(strict_types=1);

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Companies/Index', [
            'company' => CompanyResource::make(auth()->user()->company()->with('shifts')->first()),
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $attributes = $request->validated();

        if (empty($attributes)) {
            return back();
        }

        if (isset($attributes['shifts_begins_at']) && isset($attributes['shifts_ends_at'])) {
            $company->update([
                'shifts_begins_at' => sprintf(
                    '%s:%s',
                    $attributes['shifts_begins_at']['hours'],
                    $attributes['shifts_begins_at']['minutes']
                ),
                'shifts_ends_at' => sprintf(
                    '%s:%s',
                    $attributes['shifts_ends_at']['hours'],
                    $attributes['shifts_ends_at']['minutes']
                ),
            ]);

            return redirect()->route('company.index')
                ->with('flash', [
                    'type' => 'success',
                    'header' => __('app.success_action'),
                    'text' => __('app.success_message'),
                ]);
        }

        if (isset($attributes['name'])) {
            $company->update($attributes);

            return redirect()->route('company.index')
                ->with('flash', [
                    'type' => 'success',
                    'header' => __('app.success_action'),
                    'text' => __('app.success_message'),
                ]);
        }

        return back()->withErrors('name', __('app.something_went_wrong'));
    }
}
