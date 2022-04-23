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
        'Chief Executive Officer',
        'Chief Operating Officer',
        'Chief Financial Officer',
        'Chief Marketing Officer',
        'Finance manager',
        'Human resources manager',
        'Accountant',
        'Worker',
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();

        Company::all()->each(function (Company $company): void {
            foreach (self::ROLES as $role) {
                Role::create([
                    'name' => $role,
                    'properties' => json_encode(['company_id' => $company->id]),
                ]);
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
