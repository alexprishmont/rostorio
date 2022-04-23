<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\ShiftRequestTypes;

final class AvailableShiftRequestTypesResource extends BaseResource
{
    public function toArray($request)
    {
        return ShiftRequestTypes::cases();
    }
}
