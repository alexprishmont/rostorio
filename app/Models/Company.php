<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasAddress;

    protected $fillable = [
        'name',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
