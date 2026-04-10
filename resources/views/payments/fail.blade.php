@extends('layouts.app')

@section('title', __('payment.fail.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('payment.fail.heading') }}</h2>
            <p>{{ __('payment.fail.subtitle') }}</p>
        </div>

        <div class="product-card">
            <p>{{ __('payment.fail.order_number') }}: <strong>{{ $order->order_number }}</strong></p>
            <p>{{ __('payment.fail.amount') }}: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>

            <div class="form-actions">
                <a class="btn" href="{{ route('payment.card', $order) }}">{{ __('payment.fail.retry') }}</a>
                <a class="secondary-btn" href="{{ route('orders') }}">{{ __('payment.fail.to_orders') }}</a>
            </div>
        </div>
    </section>
@endsection
