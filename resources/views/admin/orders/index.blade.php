@extends('layouts.admin')

@section('title', 'Замовлення')

@section('content')
    <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Список замовлень</h2>
            <a class="btn" href="{{ route('admin.orders.create') }}">Створити замовлення</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Клієнт</th>
                    <th>Статус</th>
                    <th>Оплата</th>
                    <th>Сума</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->status_label }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td>{{ number_format($order->total, 0, ',', ' ') }}₴</td>
                        <td class="table-actions">
                            <a class="secondary-btn" href="{{ route('admin.orders.show', $order) }}">Переглянути</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $orders->appends(request()->query())->links('vendor.pagination.techno') }}
    </div>
@endsection
