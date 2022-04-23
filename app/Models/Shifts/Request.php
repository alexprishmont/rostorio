<?php

declare(strict_types=1);

namespace App\Models\Shifts;

use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    protected $table = 'shift_requests';

    protected $fillable = [
        'user_id',
        'is_editable',
        'request',
        'shift_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isEditable(): bool
    {
        return $this->is_editable;
    }

    public function scopeForComingMonth(Builder $query): Builder
    {
        return $query->whereMonth('shift_at', now()->month);
    }

    public function scopeForIncomingMonth(Builder $query): Builder
    {
        return $query->whereMonth('shift_at', now()->addMonth()->month);
    }
}
