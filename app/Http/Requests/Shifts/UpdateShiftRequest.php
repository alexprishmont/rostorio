<?php

declare(strict_types=1);

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'numeric', Rule::exists('users', 'user_id')],
            'starts_at' => ['required', 'date:Y-m-d H:i'],
            'ends_at' => ['required', 'date:Y-m-d H:i'],
        ];
    }
}
