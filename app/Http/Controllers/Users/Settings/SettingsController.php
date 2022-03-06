<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\Settings;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Settings');
    }
}
