<?php

declare(strict_types=1);

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'address.country' => ['nullable', 'string'],
            'address.city' => ['nullable', 'string'],
            'address.street' => ['nullable', 'string'],
            'address.house_number' => ['nullable', 'string'],
            'address.zip' => ['nullable', 'string'],
            'address.phone' => ['nullable', 'string'],
            'role.id' => ['nullable', 'numeric'],
            'role.name' => ['nullable', 'string'],
            'role.oldRoleId' => ['nullable', 'numeric'],
        ];
    }
}
