<?php

namespace App\Policies;

use App\Enums\Permissions;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::ACCESS_COMPANY_EDIT_PAGE->value);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasPermissionTo(Permissions::EDIT_COMPANY_NAME->value)
            || $user->hasPermissionTo(Permissions::EDIT_SHIFT_TIMES->value);
    }
}
