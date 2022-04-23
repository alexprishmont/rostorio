<?php

declare(strict_types=1);

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request' => ['required', 'string'],
            'shift_at' => ['required', 'date'],
        ];
    }
}
