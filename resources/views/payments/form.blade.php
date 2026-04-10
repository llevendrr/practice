@extends('layouts.app')

@section('title', __('payment.form.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('payment.form.heading', ['order' => $order->order_number]) }}</h2>
            <p>{{ __('payment.form.subtitle') }}</p>
        </div>

        <div class="section-grid">
            <div class="product-card">
                <h3>{{ __('payment.form.order_data') }}</h3>
                <p>{{ __('payment.form.customer') }}: {{ $order->name }}</p>
                <p>Email: {{ $order->email }}</p>
                <p>{{ __('payment.form.delivery_method') }}: {{ $order->shipping_method }}</p>
                @if ($order->postal_code)
                    <p>{{ __('payment.form.postal_code') }}: {{ $order->postal_code }}</p>
                @endif
                <p>{{ __('payment.form.status') }}: <strong>{{ $order->status_label }}</strong></p>
                <p class="muted-note">{{ __('payment.form.amount') }}: <strong>{{ number_format($order->total, 0, ',', ' ') }} ãðí</strong></p>
            </div>

            <div class="product-card">
                <h3>{{ __('payment.form.pay_card') }}</h3>
                <form id="card-payment-form" method="post" action="{{ route('payment.card.process', $order) }}">
                    @csrf

                    <div class="form-grid">
                        <div class="field-group">
                            <label for="card_number">{{ __('payment.form.card_number') }}</label>
                            <input
                                id="card_number"
                                name="card_number"
                                type="text"
                                inputmode="numeric"
                                autocomplete="cc-number"
                                maxlength="19"
                                placeholder="0000 0000 0000 0000"
                                value="{{ old('card_number') }}"
                                data-credit-mask="card-number"
                            />
                            @error('card_number')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="cardholder_name">{{ __('payment.form.cardholder') }}</label>
                            <input
                                id="cardholder_name"
                                name="cardholder_name"
                                type="text"
                                autocomplete="cc-name"
                                value="{{ old('cardholder_name') }}"
                            />
                            @error('cardholder_name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="exp_month">{{ __('payment.form.exp_month') }}</label>
                            <input
                                id="exp_month"
                                name="exp_month"
                                type="number"
                                inputmode="numeric"
                                min="1"
                                max="12"
                                placeholder="MM"
                                value="{{ old('exp_month') }}"
                            />
                            @error('exp_month')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="exp_year">{{ __('payment.form.exp_year') }}</label>
                            <input
                                id="exp_year"
                                name="exp_year"
                                type="number"
                                inputmode="numeric"
                                min="{{ now()->year }}"
                                max="{{ now()->year + 20 }}"
                                placeholder="YYYY"
                                value="{{ old('exp_year') }}"
                            />
                            @error('exp_year')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="cvv">{{ __('payment.form.cvv') }}</label>
                            <input
                                id="cvv"
                                name="cvv"
                                type="password"
                                inputmode="numeric"
                                maxlength="4"
                                placeholder="•••"
                                value="{{ old('cvv') }}"
                                data-credit-mask="cvv"
                            />
                            @error('cvv')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <p class="muted-note">{{ __('payment.form.submit_note') }}</p>
                    </div>

                    <button class="btn" type="submit" data-processing-text="{{ __('payment.form.processing') }}">{{ __('payment.form.submit') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('card-payment-form');
            if (! form) {
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            if (! submitButton) {
                return;
            }

            const processingText = submitButton.dataset.processingText || '{{ __('payment.form.processing') }}';

            form.addEventListener('submit', () => {
                if (submitButton.disabled) {
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = processingText;
            });
        });
    </script>
@endpush
