<?php

namespace App\Http\Requests;

use App\Http\Controllers\CheckoutController;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\d{9,15}$/'],
            'shipping_method' => 'required|in:' . implode(',', array_keys(CheckoutController::SHIPPING_RATES)),
            'city' => 'nullable|string|max:120|required_if:shipping_method,Нова пошта,Укрпошта',
            'street' => 'nullable|string|max:150|required_if:shipping_method,Нова пошта,Укрпошта',
            'house' => 'nullable|string|max:20|required_if:shipping_method,Нова пошта,Укрпошта',
            'apartment' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:500',
            'postal_code' => 'required|numeric|digits:5',
        ];
    }
}
