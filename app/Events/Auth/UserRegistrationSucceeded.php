<?php

declare(strict_types=1);

namespace App\Events\Auth;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistrationSucceeded
{
    use SerializesModels;
    use Dispatchable;

    public function __construct(
        public readonly User $user
    ) {
    }
}
