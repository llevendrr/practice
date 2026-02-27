<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_number' => ['required', 'digits:16'],
            'card_holder' => ['required', 'string', 'max:255'],
            'expiration' => ['required', 'regex:/^(0[1-9]|1[0-2])\\/\\d{2}$/'],
            'cvv' => ['required', 'digits:3'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $cardNumber = preg_replace('/\\D+/', '', $this->card_number ?? '');
        $cvv = preg_replace('/\\D+/', '', $this->cvv ?? '');
        $expiration = preg_replace('/[^\\d\\/]/', '', $this->expiration ?? '');

        $this->merge([
            'card_number' => $cardNumber,
            'cvv' => $cvv,
            'expiration' => $expiration,
            'card_holder' => trim((string) $this->card_holder),
        ]);
    }
}
