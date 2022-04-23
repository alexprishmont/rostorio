<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shifts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Shifts\Shift;
use App\Services\Shifts\ShiftPlannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ShiftsController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('company.index');
    }

    public function create(): Response
    {
        return Inertia::render('Companies/Shifts/Create', [
            'accounts' => UserResource::collection(Auth::user()->company->accounts()->get()),
        ]);
    }

    public function generate(string $date, ShiftPlannerService $service): RedirectResponse
    {
        $date = Carbon::parse($date)->startOfMonth();
        /**
         * @var Company $company
         */
        $company = Auth::user()->company;

        $shifts = $company->shifts()
            ->whereBetween('starts_at', [
                $date,
                $date->copy()->endOfMonth(),
            ]);

        if ($shifts->count() > 0) {
            return back()->withErrors([
                __('app.schedule.cannot_generate'),
            ]);
        }

        $service->getShiftsForMonth($date->format('Y-m'));
        $errors = [];

        foreach ($service->getErrors() as $error) {
            $errors[] = sprintf(
                '%s',
                __(
                    'app.schedule.constraints.'.$error['code'],
                    [
                        'name' => sprintf('%s %s', $error['worker']->firstname, $error['worker']->lastname),
                        'date' => $error['shift'],
                    ]
                ),
            );
        }

        if (! empty($errors)) {
            return back()->withErrors($errors);
        }

        return back()
            ->with('flash', [
                'type' => 'success',
                'header' => __('app.success_action'),
                'text' => 'Next month schedule generating process successfully has been started.',
            ]);
    }

    public function save(Request $request, ShiftPlannerService $service): RedirectResponse
    {
        $shifts = $request->all();
        $errors = [];

        $service->initShifts($shifts);

        foreach ($service->getShifts() as $shift) {
            $worker = $shift['worker'];
            $date = Carbon::parse($shift['starts_at']);

            $service->fulfillsHardConstraints($worker, $date);
        }

        $serviceErrors = $service->getErrors();
        foreach ($serviceErrors as $serviceError) {
            $errors[] = sprintf(
                '%s',
                __(
                    'app.schedule.constraints.'.$serviceError['code'],
                    [
                        'name' => sprintf(
                            '%s %s',
                            $serviceError['worker']->firstname,
                            $serviceError['worker']->lastname
                        ),
                        'date' => $serviceError['shift'],
                    ]
                ),
            );
        }

        if (! empty($errors)) {
            return back()->withErrors($errors);
        }

        $service->save();

        session()->flash('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => 'Changes successfully saved.',
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => 'Changes successfully saved.',
        ]);
    }

    public function getByDate(string $date)
    {
        $date = Carbon::parse($date);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $shifts = Shift::query()
            ->whereBetween('starts_at', [$startOfMonth, $endOfMonth])
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return ShiftResource::collection($shifts);
    }
}
