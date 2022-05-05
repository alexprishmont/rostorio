<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shifts\StoreShiftRequest;
use App\Models\Shifts\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ShiftRequestsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Work/Requests/Index');
    }

    public function store(StoreShiftRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        /**
         * @var User $user
         */
        $user = Auth::user();
        $user->shiftRequests()->updateOrCreate(
            ['shift_at' => $attributes['shift_at']],
            $attributes,
        );

        return back()->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('app.success_action'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->delete();

        return back()->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('app.success_action'),
        ]);
    }
}
