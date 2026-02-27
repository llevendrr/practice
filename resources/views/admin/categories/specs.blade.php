@extends('layouts.admin')

@section('title', 'Характеристики категорії')

@section('content')
    <div class="admin-card">
        <h2>Характеристики для {{ $category->name }}</h2>
        <p>Створюйте поля, які будуть доступні у формі товару.</p>
    </div>

    <div class="admin-card">
        <h3>Нова характеристика</h3>
        <form action="{{ route('admin.categories.specs.store', $category) }}" method="post">
            @csrf
            <div class="form-grid">
                <div class="field-group">
                    <label for="label">Назва</label>
                    <input id="label" name="label" value="{{ old('label') }}" />
                </div>
                <div class="field-group">
                    <label for="key">Ключ</label>
                    <input id="key" name="key" value="{{ old('key') }}" />
                </div>
                <div class="field-group">
                    <label for="field_type">Тип</label>
                    <select id="field_type" name="field_type">
                        <option value="text">Текст</option>
                        <option value="number">Число</option>
                    </select>
                </div>
                <div class="field-group">
                    <label>
                        <input type="checkbox" name="required" value="1" {{ old('required') ? 'checked' : '' }} />
                        Обов’язкове поле
                    </label>
                </div>
                <div class="field-group" style="flex-basis: 100%;">
                    <label for="options">Опції для select (кожна з нового рядка)</label>
                    <textarea id="options" name="options" rows="3">{{ old('options') }}</textarea>
                </div>
            </div>
            <button class="btn" type="submit">Додати</button>
        </form>
    </div>

    <div class="section-grid">
        @foreach ($fields as $field)
            <div class="feature-card">
                <form action="{{ route('admin.categories.specs.update', [$category, $field]) }}" method="post" class="form-grid">
                    @csrf
                    @method('patch')
                    <div class="field-group">
                        <label>Назва</label>
                        <input name="label" value="{{ old('label', $field->label) }}" />
                    </div>
                    <div class="field-group">
                        <label>Ключ</label>
                        <input name="key" value="{{ old('key', $field->key) }}" />
                    </div>
                    <div class="field-group">
                        <label>Тип</label>
                        <select name="field_type">
                            <option value="text" {{ $field->field_type === 'text' ? 'selected' : '' }}>Текст</option>
                            <option value="number" {{ $field->field_type === 'number' ? 'selected' : '' }}>Число</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label>
                            <input type="checkbox" name="required" value="1" {{ $field->required ? 'checked' : '' }} />
                            Обов’язкове
                        </label>
                    </div>
                    <div class="field-group" style="flex-basis: 100%;">
                        <label>Опції для select (одна в рядку)</label>
                        @php
                            $optionsValue = implode("\n", $field->options ?? []);
                        @endphp
                        <textarea name="options" rows="3">{{ old('options', $optionsValue) }}</textarea>
                    </div>
                    <div class="field-group" style="flex-basis:100%;">
                        <button class="btn" type="submit">Зберегти</button>
                    </div>
                </form>
                <form action="{{ route('admin.categories.specs.destroy', [$category, $field]) }}" method="post" class="table-actions">
                    @csrf
                    @method('delete')
                    <button class="secondary-btn" type="submit">Видалити</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
