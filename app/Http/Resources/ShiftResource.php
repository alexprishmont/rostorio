<?php

declare(strict_types=1);

namespace App\Http\Resources;

final class ShiftResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => sprintf('%s %s', $this->worker->firstname, $this->worker->lastname),
            'start' => $this->starts_at->format('Y-m-d H:i'),
            'end' => $this->ends_at->format('Y-m-d H:i'),
            'allDay' => false,
            'worker' => $this->when($this->worker, UserResource::make($this->worker)),
        ];
    }
}
