@extends('layouts.admin')

@section('title', 'Створення замовлення')

@section('content')
    <div class="admin-card">
        <h2>Нове замовлення</h2>
        <form action="{{ route('admin.orders.store') }}" method="post" class="form-grid">
            @csrf

            <div class="field-group">
                <label for="user_id">Користувач (опціонально)</label>
                <select id="user_id" name="user_id">
                    <option value="">—</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label for="name">ПІБ</label>
                <input id="name" name="name" />
            </div>
            <div class="field-group">
                <label for="email">Email</label>
                <input id="email" name="email" />
            </div>
            <div class="field-group">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" />
            </div>
            <div class="field-group">
                <label for="shipping_method">Доставка</label>
                <select id="shipping_method" name="shipping_method">
                    @foreach (['Нова пошта', 'Укрпошта', 'Самовивіз'] as $method)
                        <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label for="shipping_cost">Вартість доставки</label>
                <input id="shipping_cost" name="shipping_cost" type="number" value="0" />
            </div>
            <div class="field-group">
                <label for="status">Статус</label>
                <select id="status" name="status">
                    @foreach (\App\Models\Order::statusLabels() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label for="payment_status">Оплата</label>
                <select id="payment_status" name="payment_status">
                    <option value="pending">pending</option>
                    <option value="paid">paid</option>
                </select>
            </div>
            <div class="field-group" style="grid-column: span 2;">
                <label for="notes">Коментар</label>
                <textarea id="notes" name="notes"></textarea>
            </div>

            <div style="grid-column: span 2;">
                <h3>Товари</h3>
            </div>

            @for ($i = 0; $i < 3; $i++)
                <div class="field-group">
                    <label>Продукт</label>
                    <select name="products[{{ $i }}][product_id]">
                        <option value="">—</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label>Кількість</label>
                    <input type="number" min="0" name="products[{{ $i }}][quantity]" value="0" />
                </div>
            @endfor

            <button class="btn" type="submit">Створити</button>
        </form>
    </div>
@endsection
