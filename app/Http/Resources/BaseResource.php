<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

abstract class BaseResource extends JsonResource
{
    public function only(...$attributes): array
    {
        return Arr::only($this->resolve(), $attributes);
    }
}
