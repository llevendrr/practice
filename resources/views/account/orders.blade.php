@extends('layouts.app')

@section('title', 'Мій кабінет')

@section('content')
    @include('account.partials.tabs')

    <section class="section">
        <div class="section-heading">
            <h2>Мій профіль</h2>
            <p>Історія замовлень</p>
        </div>

        <div class="product-card">
            <h3>{{ auth()->user()->name }}</h3>
            <p>{{ auth()->user()->email }}</p>
        </div>

        <div class="table-wrap">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>№ замовлення</th>
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
                            <td>{{ $order->status_label }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>{{ number_format($order->total, 0, ',', ' ') }}₴</td>
                            <td class="table-actions">
                                @if ($order->cancelable())
                                    <form action="{{ route('orders.cancel', $order) }}" method="post">
                                        @csrf
                                        <button class="secondary-btn" type="submit">Скасувати</button>
                                    </form>
                                @endif
                                @if ($order->payment_status === \App\Models\Order::PAYMENT_PENDING)
                                    <a class="secondary-btn" href="{{ route('payment.select', $order) }}">Оплатити</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $orders->links('vendor.pagination.techno') }}
        </div>
    </section>
@endsection
