@extends('layouts.admin')

@section('title', isset($product) ? 'Редагування товару' : 'Новий товар')

@section('content')
    @php
        $specValues = old('spec_values', $product->specifications ?? []);
        $specUrl = route('admin.categories.spec-fields', ['category' => '__CATEGORY__']);
        $specFields = $specFields ?? collect();
        $selectedCategory = old('category_id', $product->category_id ?? '');
    @endphp

    <div class="admin-card">
        <h2>{{ isset($product) ? 'Редагувати товар' : 'Створити товар' }}</h2>
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('patch')
            @endif

            <div class="form-grid">
                <div class="field-group">
                    <label for="name">Назва</label>
                    <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" />
                </div>
                <div class="field-group">
                    <label for="category_id">Категорія</label>
                    <select
                        id="category_id"
                        name="category_id"
                        data-spec-select
                        data-spec-url="{{ $specUrl }}"
                        data-spec-values='@json($specValues)'
                    >
                        <option value="" {{ blank($selectedCategory) ? 'selected' : '' }}>
                            Оберіть категорію
                        </option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="muted-note" data-spec-hint {{ blank($selectedCategory) ? '' : 'hidden' }}>
                        Спочатку оберіть категорію, щоб побачити набір характеристик.
                    </p>
                </div>
                <div class="field-group" id="specs">
                    <label>Характеристики</label>
                    <div
                        id="spec-fields"
                        data-spec-fields
                        class="section-grid spec-grid {{ blank($selectedCategory) ? 'spec-grid--disabled' : '' }}"
                        aria-disabled="{{ blank($selectedCategory) ? 'true' : 'false' }}"
                    >
                        @if ($specFields->isEmpty())
                            <p class="muted-note">
                                {{ blank($selectedCategory)
                                    ? 'Спочатку оберіть категорію, щоб побачити набір характеристик.'
                                    : 'Для цієї категорії не задано характеристик.' }}
                            </p>
                        @else
                            @foreach ($specFields as $field)
                                @php
                                    $value = $specValues[$field->key] ?? '';
                                @endphp
                                <div class="field-group">
                                    <label>{{ $field->label }} @if ($field->required) <span class="error-text">*</span> @endif</label>
                                    @if ($field->field_type === 'number')
                                        <input
                                            type="number"
                                            step="any"
                                            name="spec_values[{{ $field->key }}]"
                                            value="{{ $value }}"
                                            placeholder="Вкажіть {{ $field->label }}"
                                            {{ $field->required ? 'required' : '' }}
                                        />
                                    @elseif ($field->field_type === 'select')
                                        <select
                                            name="spec_values[{{ $field->key }}]"
                                            {{ $field->required ? 'required' : '' }}
                                        >
                                            <option value="">Виберіть {{ mb_strtolower($field->label) }}</option>
                                            @foreach ($field->options ?? [] as $option)
                                                <option value="{{ $option }}" {{ $option === $value ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input
                                            name="spec_values[{{ $field->key }}]"
                                            value="{{ $value }}"
                                            placeholder="Вкажіть {{ $field->label }}"
                                            {{ $field->required ? 'required' : '' }}
                                        />
                                    @endif
                                    @error("spec_values.{$field->key}")
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="field-group">
                    <label for="slug">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" />
                </div>
                <div class="field-group">
                    <label for="brand">Бренд</label>
                    <input id="brand" name="brand" value="{{ old('brand', $product->brand ?? '') }}" />
                </div>
                <div class="field-group">
                    <label for="model">Модель</label>
                    <input id="model" name="model" value="{{ old('model', $product->model ?? '') }}" />
                </div>
                <div class="field-group">
                    <label for="price">Ціна</label>
                    <input id="price" name="price" type="number" value="{{ old('price', $product->price ?? 0) }}" />
                </div>
                <div class="field-group">
                    <label for="discount">Знижка</label>
                    <input id="discount" name="discount" type="number" value="{{ old('discount', $product->discount ?? 0) }}" />
                </div>
                <div class="field-group">
                    <label for="stock">На складі</label>
                    <input id="stock" name="stock" type="number" value="{{ old('stock', $product->stock ?? 0) }}" />
                </div>
                <div class="field-group">
                    <label for="description">Опис</label>
                    <textarea id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                <div class="field-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} />
                        Активний
                    </label>
                </div>
                <div class="field-group">
                    <label>
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ? 'checked' : '' }} />
                        Новинка
                    </label>
                </div>
                <div class="field-group">
                    <label>
                        <input type="checkbox" name="is_hit" value="1" {{ old('is_hit', $product->is_hit ?? false) ? 'checked' : '' }} />
                        Хіт
                    </label>
                </div>
                <div class="field-group image-upload-field">
                    <label for="image">Головне фото</label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/jpg,image/png,image/webp"
                    />
                    <p class="muted-note">
                        Якщо товар уже має головне фото, новий файл замінить його.
                    </p>
                    @error('image')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
                <div class="field-group image-upload-field">
                    <label for="images">Фото</label>
                    <input
                        id="images"
                        name="images[]"
                        type="file"
                        multiple
                        accept="image/jpeg,image/jpg,image/png,image/webp"
                    />
                    <p class="muted-note">
                        Підтримуються JPG, JPEG, PNG та WEBP. Максимальний розмір — 2 МБ на файл.
                    </p>
                    @error('images')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @if ($product && $product->images->isNotEmpty())
                <div class="feature-card">
                    <h3>Існуючі фото</h3>
                    <div class="product-image-grid">
                        @foreach ($product->images as $image)
                            <article class="product-image-card">
                                <img src="{{ $image->url }}" alt="Фото товару {{ $product->name }}" />
                                <div class="product-image-card__actions">
                                    <label class="image-radio">
                                        <input type="radio" name="main_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }} />
                                        <span>Головне фото</span>
                                    </label>
                                    <div class="product-image-card__controls">
                                        <div class="product-image-card__sort">
                                            <span>Сортування</span>
                                            <input type="number" name="image_sort[{{ $image->id }}]" value="{{ $image->sort_order }}" />
                                        </div>
                                        <label class="image-delete">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" />
                                            <span>Видалити</span>
                                        </label>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <button class="btn" type="submit" name="action" value="save">Зберегти товар</button>
        </form>
    </div>
@endsection
