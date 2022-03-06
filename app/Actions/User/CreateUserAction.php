<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class CreateUserAction
{
    public function execute(array $attributes): ?User
    {
        if (! isset($attributes['password'])) {
            $attributes = array_merge(
                ['password' => $this->generatePassword($attributes)],
                $attributes
            );
        }

        $user = User::create($attributes);

        if ($user) {
            return $user;
        }

        return null;
    }

    private function generatePassword(array $attributes): string
    {
        return sprintf(
            '%s%s',
            strtolower($attributes['firstname']),
            strtolower($attributes['lastname'])
        );
    }
}
