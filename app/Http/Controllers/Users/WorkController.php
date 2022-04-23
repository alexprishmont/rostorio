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
        return Inertia::render('Users/Work/Index');
    }
}
