@extends('layouts.app')

@section('title', 'Оплата замовлення')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Оплата замовлення {{ $order->order_number }}</h2>
            <p>Платіть безпечно карткою — ми не зберігаємо її дані.</p>
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
                <h3>Оплата карткою</h3>
                <form method="post" action="{{ route('payment.process', $order) }}">
                    @csrf
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="card_number">Номер картки</label>
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
                            <label for="card_holder">Ім'я власника</label>
                            <input
                                id="card_holder"
                                name="card_holder"
                                type="text"
                                autocomplete="cc-name"
                                value="{{ old('card_holder') }}"
                            />
                            @error('card_holder')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="expiration">Термін дії (MM/YY)</label>
                            <input
                                id="expiration"
                                name="expiration"
                                type="text"
                                inputmode="numeric"
                                maxlength="5"
                                placeholder="MM/YY"
                                value="{{ old('expiration') }}"
                                data-credit-mask="expiration"
                            />
                            @error('expiration')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="cvv">CVV</label>
                            <input
                                id="cvv"
                                name="cvv"
                                type="password"
                                inputmode="numeric"
                                maxlength="3"
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
                        <p class="muted-note">Всі поля обов'язкові. Дані не зберігаються.</p>
                    </div>

                    <button class="btn" type="submit">Оплатити</button>
                </form>
            </div>
        </div>
    </section>
@endsection
