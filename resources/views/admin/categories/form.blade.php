@extends('layouts.admin')

@section('title', isset($category) ? 'Редагувати категорію' : 'Створити категорію')

@section('content')
    <div class="admin-card">
        <h2>{{ isset($category) ? 'Редагування категорії' : 'Нова категорія' }}</h2>
        <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="post" class="form-grid">
            @csrf
            @if (isset($category))
                @method('patch')
            @endif

            <div class="field-group">
                <label for="name">Назва</label>
                <input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" />
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}" />
                @error('slug')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label for="parent_id">Батьківська категорія</label>
                <select id="parent_id" name="parent_id">
                    <option value="">—</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="order">Порядок</label>
                <input id="order" name="order" type="number" value="{{ old('order', $category->order ?? 0) }}" />
            </div>

            <div class="field-group">
                <label for="is_active">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active ?? 1) ? 'checked' : '' }} />
                    Активна
                </label>
            </div>

            <div class="field-group">
                <label for="description">Опис</label>
                <textarea id="description" name="description">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <button class="btn" type="submit">Зберегти</button>
        </form>
    </div>
@endsection
