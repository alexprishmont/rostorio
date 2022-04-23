<?php

declare(strict_types=1);

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'unique:companies'],
            'shifts_begins_at' => ['nullable', 'array'],
            'shifts_ends_at' => ['nullable', 'array'],
        ];
    }
}
