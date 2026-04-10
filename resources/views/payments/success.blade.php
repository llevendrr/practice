@extends('layouts.app')

@section('title', __('payment.success.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('payment.success.heading') }}</h2>
            <p>{{ __('payment.success.subtitle', ['order' => $order->order_number]) }}</p>
        </div>

        <div class="product-card">
            <p>{{ __('payment.success.order_number') }}: <strong>{{ $order->order_number }}</strong></p>
            <p>{{ __('payment.success.amount') }}: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>
            @if ($order->payment_reference)
                <p>{{ __('payment.success.reference') }}: <strong>{{ $order->payment_reference }}</strong></p>
            @endif
            <p>{{ __('payment.success.paid_at') }}: <strong>{{ $order->paid_at ? $order->paid_at->format('d.m.Y H:i') : '—' }}</strong></p>

            <div class="form-actions">
                <a class="btn" href="{{ route('orders') }}">{{ __('payment.success.to_orders') }}</a>
                <a class="secondary-btn" href="{{ route('home') }}">{{ __('payment.success.to_home') }}</a>
            </div>
        </div>
    </section>
@endsection
