<?php

declare(strict_types=1);

namespace App\Http\Resources;

class EmployeeResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullname' => sprintf('%s %s', $this->firstname, $this->lastname),
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d'),
            'roles' => $this->when($this->roles, $this->roles),
            'address' => new AddressResource($this->address),
            'requests' => ShiftRequestsResource::collection($this->shiftRequests),
        ];
    }
}
