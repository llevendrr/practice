<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'phone' => 'nullable|digits_between:9,15',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
