<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;

/**
 * @mixin User
 */
final class UserResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'name' => sprintf('%s %s', $this->firstname, $this->lastname),
            'email' => $this->email,
            'requests' => ShiftRequestsResource::collection($this->shiftRequests),
        ];
    }
}
