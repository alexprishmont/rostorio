<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserActivity
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            $expiresAt = now()->addMinutes(2);
            $user = Auth::user();
            $cacheKey = sprintf('users/%d/is-online', $user->id);
            Cache::put($cacheKey, true, $expiresAt);

            $user->last_seen = now();
            $user->save();
        }

        return $next($request);
    }
}
