@extends('layouts.admin')

@section('title', 'Категорії')

@section('content')
    <div class="admin-card">
        <h2>Категорії</h2>
        <a class="btn" href="{{ route('admin.categories.create') }}">Створити категорію</a>
    </div>

    <div class="table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Назва</th>
                    <th>Батько</th>
                    <th>Порядок</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent?->name ?? '-' }}</td>
                        <td>{{ $category->order }}</td>
                        <td>{{ $category->is_active ? 'Активна' : 'Неактивна' }}</td>
                        <td class="table-actions">
                            <a class="secondary-btn" href="{{ route('admin.categories.edit', $category) }}">Редагувати</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="secondary-btn" type="submit">Видалити</button>
                            </form>
                            <a class="secondary-btn" href="{{ route('admin.categories.specs.index', $category) }}">Характеристики</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $categories->appends(request()->query())->links('vendor.pagination.techno') }}
    </div>
@endsection
