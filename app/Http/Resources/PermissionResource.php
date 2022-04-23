<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Spatie\Permission\Models\Permission;

/**
 * @mixin Permission
 */
final class PermissionResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen($this->created_at, ['created_at' => $this->created_at->format('Y-m-d H:i:s')]),
            $this->mergeWhen($this->updated_at, ['updated_at' => $this->updated_at->format('Y-m-d H:i:s')]),
        ];
    }
}
