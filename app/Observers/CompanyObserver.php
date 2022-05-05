<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Company;
use Spatie\Permission\Models\Role;

class CompanyObserver
{
    private const ROLES = [
        'admin' => 'Įmonės savininkas',
        'moderator' => 'Žmogiškųjų išteklių vadybininkas',
        'user' => 'Darbuotojas',
    ];

    public function created(Company $company): void
    {
        foreach (self::ROLES as $code => $role) {
            Role::create([
                'name' => $role,
                'guard_name' => 'company_'.$company->id,
                'properties' => json_encode([
                    'role_code' => $code,
                ]),
            ]);
        }
    }

    public function deleted(Company $company): void
    {
        $companyRoles = Role::query()
            ->where('guard_name', 'company_'.$company->id)
            ->get();

        $companyRoles->each->delete();
    }
}
