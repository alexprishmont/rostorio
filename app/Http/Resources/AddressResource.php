<?php

declare(strict_types=1);

namespace App\Http\Resources;

class AddressResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'city' => $this->when($this->city, $this->city),
            'country' => $this->when($this->country, $this->country),
            'street' => $this->when($this->street, $this->street),
            'house_number' => $this->when($this->house_number, $this->house_number),
            'zip' => $this->when($this->zip, $this->zip),
            'phone' => $this->when($this->phone, $this->phone),
        ];
    }
}
