<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::all()->each->delete();

        foreach (Permissions::cases() as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
