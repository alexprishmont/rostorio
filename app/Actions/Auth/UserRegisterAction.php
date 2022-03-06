<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\User\CreateUserAction;
use App\Events\Auth\UserRegistrationSucceeded;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserRegisterAction
{
    public function __construct(private CreateUserAction $createUserAction)
    {
    }

    public function execute(array $credentials): RedirectResponse
    {
        $user = $this->createUserAction->execute($credentials);

        if (! $user) {
            return back()->withErrors('email', __('app.something_went_wrong'));
        }

        Auth::login($user);

        UserRegistrationSucceeded::dispatch($user);

        return redirect()->intended('/dashboard')
            ->with('flash', [
                'type' => 'success',
                'header' => __('app.success_action'),
                'text' => __('auth.registration_success'),
            ]);
    }
}
