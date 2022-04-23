<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Spatie\Permission\Models\Role;

/**
 * @mixin Role
 */
final class RoleResource extends BaseResource
{
    public function toArray($request): array
    {
        $this->load('permissions');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => PermissionResource::collection($this->permissions),
        ];
    }
}
