@extends('layouts.app')

@section('title', 'Оплата успішна')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>🎉 Дякуємо за оплату!</h2>
            <p>Ваше замовлення {{ $order->order_number }} успішно оплачено. Ми обробляємо заявку і повідомимо, як тільки вона буде відправлена.</p>
        </div>

        <div class="product-card">
            <p>Номер замовлення: <strong>{{ $order->order_number }}</strong></p>
            <p>Сума: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>
            <div class="form-actions">
                <a class="btn" href="{{ route('orders') }}">Перейти до замовлень</a>
                <a class="secondary-btn" href="{{ route('home') }}">На головну</a>
            </div>
        </div>
    </section>
@endsection
