<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserLoginAction
{
    public function execute(array $credentials): RedirectResponse
    {
        if (Auth::attempt($credentials)) {
            Request::session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => __('auth.login_error_message'),
        ]);
    }
}
