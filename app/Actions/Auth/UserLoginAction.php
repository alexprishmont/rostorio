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

            return redirect()->intended('/dashboard')
                ->with('flash', [
                    'type' => 'success',
                    'header' => __('app.success_action'),
                    'text' => __('auth.login_success'),
                ]);
        }

        return back()->withErrors([
            'email' => __('auth.login_error_message'),
        ]);
    }
}
