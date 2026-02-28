@extends('layouts.app')

@section('title', 'Вибір способу оплати')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Виберіть спосіб оплати для замовлення {{ $order->order_number }}</h2>
            <p>Післяплата обробляється офлайн. Щоб завершити оплату онлайн, перейдіть до форми картки.</p>
        </div>

        <div class="section-grid">
            <div class="product-card">
                <h3>Дані замовлення</h3>
                <p>Клієнт: {{ $order->name }}</p>
                <p>Email: {{ $order->email }}</p>
                <p>Метод доставки: {{ $order->shipping_method }}</p>
                @if ($order->postal_code)
                    <p>Індекс: {{ $order->postal_code }}</p>
                @endif
                <p>Статус: <strong>{{ $order->status_label }}</strong></p>
                <p class="muted-note">Сума до оплати: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>
            </div>
            <div class="product-card">
                <h3>Спосіб оплати</h3>
                <form method="post" action="{{ route('payment.select.cod', $order) }}">
                    @csrf
                    <p>Післяплата > підтверджуємо замовлення та чекаємо оплату при отриманні. Статус зміниться на «Оплачено» після обробки.</p>
                    <button class="secondary-btn secondary-btn--danger" type="submit">Підтвердити післяплату</button>
                </form>

                <div class="form-actions">
                    <p class="muted-note">Або</p>
                </div>

                <a class="btn" href="{{ route('payment.card', $order) }}">Оплата карткою онлайн</a>
                <p class="muted-note">Онлайн-оплата відкриє форму введення картки, після успішного підтвердження статус замовлення зміниться на «Оплачено».</p>
            </div>
        </div>
    </section>
@endsection
