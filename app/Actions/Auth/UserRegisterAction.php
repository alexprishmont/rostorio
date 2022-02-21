<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\UserRegistrationSucceeded;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserRegisterAction
{
    public function execute(array $credentials): RedirectResponse
    {
        $user = User::create($credentials);

        if (! $user) {
            return back()->withErrors('email', __('app.something_went_wrong'));
        }

        Auth::login($user);

        UserRegistrationSucceeded::dispatch($user);

        return redirect()->intended('/dashboard')
            ->with('message', ['success' => __('auth.registration_success')]);
    }
}
