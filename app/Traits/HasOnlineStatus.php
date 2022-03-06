<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasOnlineStatus
{
    public function getStatus(): bool
    {
        $cacheKey = sprintf('%s/%d/is-online', $this->getTable(), $this->id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        return false;
    }
}
