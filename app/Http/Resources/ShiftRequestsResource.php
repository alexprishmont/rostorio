<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Shifts\Request;

/**
 * @mixin Request
 */
final class ShiftRequestsResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => ucfirst(str_replace('_', ' ', $this->request)),
            'start' => $this->shift_at,
            'allDay' => true,
        ];
    }
}
