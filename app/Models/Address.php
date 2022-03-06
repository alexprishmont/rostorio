<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $fillable = [
        'city',
        'country',
        'street',
        'house_number',
        'zip',
        'phone',
        'addressable_id',
        'addressable_type',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
