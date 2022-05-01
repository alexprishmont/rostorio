<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class WorkController extends Controller
{
    public function index(): Response
    {
        $shiftTime = Auth::user()->company->shifts_begins_at->diffInHours(Auth::user()->company->shifts_ends_at);
        $shiftsCount = Auth::user()->shifts()->whereBetween(
            'starts_at',
            [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]
        )->count();

        return Inertia::render('Users/Work/Index', [
            'totalWorkHours' => $shiftTime * $shiftsCount,
            'shiftsCount' => $shiftsCount,
        ]);
    }
}
