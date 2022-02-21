<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'min:2'],
        ];
    }
}
