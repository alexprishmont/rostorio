<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    private const ROLES = [
        'admin' => 'Įmonės savininkas',
        'moderator' => 'Žmogiškųjų išteklių vadybininkas',
        'user' => 'Darbuotojas',
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();

        Company::all()->each(function (Company $company): void {
            foreach (self::ROLES as $code => $role) {
                Role::create([
                    'name' => $role,
                    'guard_name' => 'company_'.$company->id,
                    'properties' => json_encode([
                        'role_code' => $code,
                    ]),
                ]);
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
