<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategorySpecFieldRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->filled('options')) {
            $this->merge(['options' => null]);
            return;
        }

        $options = is_array($this->options)
            ? $this->options
            : preg_split('/\r?\n/', $this->options);

        $options = array_filter(array_map('trim', $options));

        $this->merge(['options' => $options]);
    }

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $fieldId = $this->route('field')?->id;

        return [
            'label' => 'required|string|max:120',
            'key' => [
                'required',
                'string',
                'alpha_dash',
                Rule::unique('category_spec_fields', 'key')
                    ->ignore($fieldId)
                    ->where(fn ($query) => $query->where('category_id', $this->route('category')->id)),
            ],
            'field_type' => ['required', Rule::in(['text', 'number', 'select'])],
            'required' => 'boolean',
            'options' => ['nullable', 'array', 'required_if:field_type,select'],
            'options.*' => ['required_with:options', 'string', 'max:80'],
        ];
    }
}
