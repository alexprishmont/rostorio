<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function store(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return redirect()->route('login')->with('flash', [
            'type' => 'success',
            'header' => __('app.success_action'),
            'text' => __('auth.successful_logout'),
        ]);
    }
}
