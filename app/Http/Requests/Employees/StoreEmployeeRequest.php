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
            'role' => ['required'],
            'created_by_organization' => ['nullable', 'bool'],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'Turi būti pateiktas darbuotojo vardas.',
            'lastname.required' => 'Turi būti pateikta darbuotojo pavardė.',
            'email.required' => 'Turi būti pateiktas elektroninis paštas.',
            'email.email' => 'Neteisingas elektroninio pašto formatas.',
            'email.unique' => 'Elektroninis paštas jau egzistuoja mūsų sistemoje.',
            'phone.required' => 'Telefono numeris turi būti pateiktas.',
            'phone.phone' => 'Telefono numerio formatas yra neteisingas.',
        ];
    }
}
