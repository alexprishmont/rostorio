<?php

declare(strict_types=1);

namespace App\Models\Shifts;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'starts_at',
        'ends_at',
    ];

    protected $dates = ['starts_at', 'ends_at'];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
