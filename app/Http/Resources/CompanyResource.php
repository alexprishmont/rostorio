<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Company;

/**
 * @mixin Company
 */
final class CompanyResource extends BaseResource
{
    public function toArray($request): array
    {
        $this->load('accounts');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'accounts' => $this->when($this->relationLoaded('accounts'), UserResource::collection($this->accounts)),
            'shifts' => $this->when($this->relationLoaded('shifts'), [
                'starts' => [
                    'hours' => $this->shifts_begins_at->format('H'),
                    'minutes' => $this->shifts_begins_at->format('i'),
                ],
                'ends' => [
                    'hours' => $this->shifts_ends_at->format('H'),
                    'minutes' => $this->shifts_ends_at->format('i'),
                ],
            ]),
        ];
    }
}
