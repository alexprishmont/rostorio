<?php

declare(strict_types=1);

namespace App\Actions\Address;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class CreateAddressAction
{
    public function execute(Model $model, array $attributes): ?Address
    {
        return $model->address()->updateOrCreate(
            [
                'addressable_id' => $model->id,
                'addressable_type' => get_class($model),
            ],
            $attributes
        );
    }
}
