@extends('layouts.app')

@section('title', 'Оплата успішно виконана')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Оплату успішно виконано</h2>
            <p>Дякуємо! Ми отримали оплату для замовлення {{ $order->order_number }}.</p>
        </div>

        <div class="product-card">
            <p>Номер замовлення: <strong>{{ $order->order_number }}</strong></p>
            <p>Сума: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>
            @if ($order->payment_reference)
                <p>Референс транзакції: <strong>{{ $order->payment_reference }}</strong></p>
            @endif
            <p>Дата оплати: <strong>{{ $order->paid_at ? $order->paid_at->format('d.m.Y H:i') : '—' }}</strong></p>

            <div class="form-actions">
                <a class="btn" href="{{ route('orders') }}">Перейти до профілю / Мої замовлення</a>
                <a class="secondary-btn" href="{{ route('home') }}">На головну</a>
            </div>
        </div>
    </section>
@endsection
