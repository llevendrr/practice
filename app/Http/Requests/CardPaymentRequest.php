<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CardPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = now()->year;

        return [
            'card_number' => ['required', 'digits_between:13,19'],
            'cardholder_name' => ['required', 'string', 'max:255'],
            'exp_month' => ['required', 'integer', 'between:1,12'],
            'exp_year' => ['required', 'integer', 'digits:4', 'min:' . $currentYear],
            'cvv' => ['required', 'regex:/^\d{3,4}$/'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'card_number' => preg_replace('/\D+/', '', $this->card_number ?? ''),
            'cvv' => preg_replace('/\D+/', '', $this->cvv ?? ''),
            'exp_month' => (int) ($this->exp_month ?? 0),
            'exp_year' => (int) ($this->exp_year ?? 0),
            'cardholder_name' => trim((string) ($this->cardholder_name ?? '')),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function () use ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (! $this->exp_month || ! $this->exp_year) {
                return;
            }

            try {
                $expiration = Carbon::createFromDate($this->exp_year, $this->exp_month, 1)->endOfMonth();
            } catch (\Throwable $exception) {
                $validator->errors()->add('exp_month', 'Невірний термін дії.');
                return;
            }

            if ($expiration->lt(Carbon::now()->startOfDay())) {
                $validator->errors()->add('exp_month', 'Термін дії карти минув.');
            }
        });
    }
}
