<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;
        $isSyncAction = $this->isSyncAction();

        $baseRules = [
            'action' => ['required', Rule::in(['save', 'sync_specs'])],
            'category_id' => ['required', 'exists:categories,id'],
            'spec_values' => ['nullable', 'array'],
            'spec_values.*' => ['nullable', 'string', 'max:255'],
        ];

        if ($isSyncAction) {
            return $baseRules;
        }

        return array_merge($baseRules, [
            'name' => ['required', 'string', 'min:3', 'max:165'],
            'slug' => array_merge(
                ['required'],
                [
                    'string',
                    'alpha_dash',
                    Rule::unique('products', 'slug')->ignore($productId),
                ]
            ),
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:60'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_new' => ['boolean'],
            'is_hit' => ['boolean'],
            'image_sort' => ['nullable', 'array'],
            'image_sort.*' => ['nullable', 'integer'],
            'main_image' => ['nullable', 'integer', 'exists:product_images,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function isSyncAction(): bool
    {
        return $this->input('action') === 'sync_specs';
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->isSyncAction()) {
                return;
            }

            $category = Category::find($this->input('category_id'));

            if (! $category) {
                return;
            }

            $fields = $category->specFields;

            foreach ($fields as $field) {
                $value = $this->input("spec_values.{$field->key}");
                $label = $field->label;

                if ($field->required && ($value === null || $value === '')) {
                    $validator->errors()->add("spec_values.{$field->key}", "Поле «{$label}» обов’язкове.");
                    continue;
                }

                if ($value === null || $value === '') {
                    continue;
                }

                if ($field->field_type === 'number' && ! is_numeric($value)) {
                    $validator->errors()->add("spec_values.{$field->key}", "Поле «{$label}» має бути числом.");
                }

                if ($field->field_type === 'select') {
                    $options = $field->options ?? [];

                    if (! in_array($value, $options, true)) {
                        $validator->errors()->add("spec_values.{$field->key}", "Поле «{$label}» має один із дозволених варіантів.");
                    }
                }
            }

            $this->ensureImagePresence($validator);
        });
    }

    private function ensureImagePresence(Validator $validator): void
    {
        if ($this->isSyncAction()) {
            return;
        }

        $product = $this->route('product');
        $existingIds = $product ? $product->images()->pluck('id')->toArray() : [];
        $deleteIds = collect($this->input('delete_images', []))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->toArray();

        $remainingCount = max(0, count($existingIds) - count(array_intersect($existingIds, $deleteIds)));

        if ($remainingCount === 0 && ! $this->hasFile('images') && ! $this->hasFile('image')) {
            $validator->errors()->add('images', 'Потрібно додати хоча б одне фото товару.');
        }
    }
}
