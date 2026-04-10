@extends('layouts.app')

@section('title', __('payment.select.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('payment.select.heading', ['order' => $order->order_number]) }}</h2>
            <p>{{ __('payment.select.subtitle') }}</p>
        </div>

        <div class="section-grid">
            <div class="product-card">
                <h3>{{ __('payment.select.order_data') }}</h3>
                <p>{{ __('payment.select.customer') }}: {{ $order->name }}</p>
                <p>Email: {{ $order->email }}</p>
                <p>{{ __('payment.select.delivery_method') }}: {{ $order->shipping_method }}</p>
                @if ($order->postal_code)
                    <p>{{ __('payment.select.postal_code') }}: {{ $order->postal_code }}</p>
                @endif
                <p>{{ __('payment.select.status') }}: <strong>{{ $order->status_label }}</strong></p>
                <p class="muted-note">{{ __('payment.select.amount') }}: <strong>{{ number_format($order->total, 0, ',', ' ') }} грн</strong></p>
            </div>
            <div class="product-card">
                <h3>{{ __('payment.select.method_title') }}</h3>
                <form method="post" action="{{ route('payment.select.cod', $order) }}">
                    @csrf
                    <p>{{ __('payment.select.cod_description') }}</p>
                    <button class="secondary-btn secondary-btn--danger" type="submit">{{ __('payment.select.cod_button') }}</button>
                </form>

                <div class="form-actions">
                    <p class="muted-note">{{ __('payment.select.or') }}</p>
                </div>

                <a class="btn" href="{{ route('payment.card', $order) }}">{{ __('payment.select.card_button') }}</a>
                <p class="muted-note">{{ __('payment.select.card_note') }}</p>
            </div>
        </div>
    </section>
@endsection
