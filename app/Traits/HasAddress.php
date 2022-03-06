<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddress
{
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
