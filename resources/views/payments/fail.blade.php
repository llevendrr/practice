@extends('layouts.app')

@section('title', 'Платіж не вдалося виконати')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Платіж не пройшов</h2>
            <p>{{ $paymentError }}</p>
        </div>

        <div class="product-card">
            <p>Номер замовлення: <strong>{{ $order->order_number }}</strong></p>
            <p>Сума: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>

            <div class="form-actions">
                <a class="btn" href="{{ route('payment.card', $order) }}">Спробувати ще раз</a>
                <a class="secondary-btn" href="{{ route('orders') }}">Мої замовлення</a>
            </div>
        </div>
    </section>
@endsection
