<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\Models\Company;

class CreateCompanyAction
{
    public function execute(array $attributes): Company
    {
        return Company::create($attributes);
    }
}
