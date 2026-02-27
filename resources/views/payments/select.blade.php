@extends('layouts.app')

@section('title', 'Вибір способу оплати')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Виберіть спосіб оплати для {{ $order->order_number }}</h2>
            <p>Після успішного оформлення замовлення оберіть, як хочете розрахуватися. Ви завжди зможете змінити рішення, доки статус заявлення не змінився на «Оплачено».</p>
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
                <p class="muted-note">Сума до оплати: <strong>{{ number_format($order->total, 0, ',', ' ') }}₴</strong></p>
            </div>
            <div class="product-card">
                <h3>Спосіб оплати</h3>
                <form method="post" action="{{ route('payment.select.cod', $order) }}">
                    @csrf
                    <p>Післяплата → підтверджуємо замовлення, але оплата відбувається під час отримання. Статус стане «В обробці». Післяплата доступна, поки замовлення не оплачено онлайн.</p>
                    <button class="secondary-btn secondary-btn--danger" type="submit">Післяплата</button>
                </form>

                <div class="form-actions">
                    <p class="muted-note">або</p>
                </div>

                <a class="btn" href="{{ route('payment', $order) }}">Оплата карткою онлайн</a>
                <p class="muted-note">Онлайн-оплата відкриє форму введення картки, після успішного підтвердження статус замовлення зміниться на «Оплачено».</p>
            </div>
        </div>
    </section>
@endsection
