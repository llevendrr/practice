@extends('layouts.admin')

@section('title', 'Замовлення ' . $order->order_number)

@section('content')
    <div class="admin-card">
        <h2>Замовлення {{ $order->order_number }}</h2>
        <p>Клієнт: {{ $order->name }} · {{ $order->email }}</p>
        <p>Сума: {{ number_format($order->total, 0, ',', ' ') }}₴</p>
        <p>Статус: {{ $order->status_label }} · Оплата: {{ $order->payment_status }}</p>
    </div>

    <div class="admin-card">
        <h3>Товари</h3>
        <div class="table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Ціна</th>
                        <th>Кількість</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->name }}</td>
                            <td>{{ number_format($item->price - $item->discount, 0, ',', ' ') }}₴</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <h3>Оновити статус</h3>
        <form action="{{ route('admin.orders.status', $order) }}" method="post" class="form-grid">
            @csrf
            @method('patch')
            <div class="field-group">
                <label for="status">Статус</label>
                <select id="status" name="status">
                    @foreach (\App\Models\Order::statusLabels() as $value => $label)
                        <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field-group">
                <label for="payment_status">Оплата</label>
                <select id="payment_status" name="payment_status">
                    @foreach (['pending','paid'] as $payment)
                        <option value="{{ $payment }}" {{ $order->payment_status === $payment ? 'selected' : '' }}>{{ $payment }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Зберегти</button>
        </form>
    </div>

    <div class="admin-card">
        <h3>Доставка</h3>
        <p>Метод: {{ $order->shipping_method }}</p>
        @if ($order->shipping_city)
            <p>Місто: {{ $order->shipping_city }}</p>
        @endif
        @if ($order->shipping_street || $order->shipping_house || $order->shipping_apartment)
            <p>Адреса:
                {{ trim(implode(', ', array_filter([
                    $order->shipping_street,
                    $order->shipping_house,
                    $order->shipping_apartment,
                ]))) }}
            </p>
        @endif
        <p>Вартість доставки: {{ number_format($order->shipping_cost, 0, ',', ' ') }}₴</p>
    </div>
@endsection
