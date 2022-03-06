<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class SetCompanyIdAction
{
    public function execute(User $user, int $companyId): bool
    {
        $user->company_id = $companyId;

        return $user->save();
    }
}
