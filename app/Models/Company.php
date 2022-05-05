<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Shifts\Shift;
use App\Traits\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    use HasAddress;

    protected $fillable = [
        'name',
        'shifts_begins_at',
        'shifts_ends_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'shifts_begins_at',
        'shifts_ends_at',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }
}
