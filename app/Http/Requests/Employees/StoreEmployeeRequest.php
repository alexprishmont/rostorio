<?php

declare(strict_types=1);

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'phone:LT'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'created_by_organization' => ['required', 'bool'],
        ];
    }
}
