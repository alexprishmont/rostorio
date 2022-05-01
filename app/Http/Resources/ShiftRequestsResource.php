<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\ShiftRequestTypes;
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
            'title' => __('requests.'.$this->request),
            'backgroundColor' => $this->getBackgroundColor($this->request),
            'borderColor' => $this->getBackgroundColor($this->request),
            'start' => $this->shift_at,
            'allDay' => true,
        ];
    }

    private function getBackgroundColor(string $request): string
    {
        return match ($request) {
            ShiftRequestTypes::WANT_TO_WORK->value => '#3f6212',
            ShiftRequestTypes::CAN_BUT_DONT_WANT_TO_WORK->value => '#a16207',
            ShiftRequestTypes::CANT_WORK->value => '#b91c1c',
            default => '#333',
        };
    }
}
