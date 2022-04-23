<?php

namespace App\Models;

use App\Models\Shifts\Request;
use App\Models\Shifts\Shift;
use App\Traits\HasAddress;
use App\Traits\HasOnlineStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasAddress;
    use HasOnlineStatus;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'last_seen',
        'created_by_organization',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function shiftRequests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    protected function password(): Attribute
    {
        return new Attribute(
            set: fn (string $value): string => Hash::make($value),
        );
    }
}
