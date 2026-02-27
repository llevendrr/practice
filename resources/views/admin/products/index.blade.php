@extends('layouts.admin')

@section('title', 'Товари')

@section('content')
    <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Товари</h2>
            <a class="btn" href="{{ route('admin.products.create') }}">Додати товар</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Назва</th>
                    <th>Категорія</th>
                    <th>Ціна</th>
                    <th>Наявність</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td>{{ number_format($product->discounted_price, 0, ',', ' ') }}₴</td>
                        <td>{{ $product->stock }}</td>
                        <td class="table-actions">
                            <a class="secondary-btn" href="{{ route('admin.products.edit', $product) }}">Редагувати</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="post">
                                @csrf
                                @method('delete')
                                <button class="secondary-btn" type="submit">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $products->appends(request()->query())->links('vendor.pagination.techno') }}
    </div>
@endsection
