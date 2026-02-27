@extends('layouts.app')

@section('title', 'Оформлення замовлення')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Оформлення замовлення</h2>
            <p>Підтвердіть контактні дані, оберіть спосіб доставки та залишіть додаткові побажання.</p>
        </div>

        <div class="section-grid">
            <form class="product-card" action="{{ route('checkout.store') }}" method="post">
                @csrf
                <div class="form-grid">
                    <div class="field-group">
                        <label for="name">Ім’я</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" />
                        @error('name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" />
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="phone">Телефон</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', auth()->user()?->phone) }}" />
                        @error('phone')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="shipping_method">Доставка</label>
                        <select id="shipping_method" name="shipping_method">
                            @foreach ($shippingMethods as $method)
                                <option value="{{ $method }}" {{ old('shipping_method') === $method ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_method')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group field-group--wide">
                        <label for="city">Місто</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}" />
                        @error('city')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="postal_code">Поштовий індекс</label>
                        <input
                            id="postal_code"
                            name="postal_code"
                            type="text"
                            inputmode="numeric"
                            maxlength="5"
                            value="{{ old('postal_code') }}"
                            placeholder="12345"
                        />
                        @error('postal_code')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="street">Вулиця</label>
                        <input id="street" name="street" type="text" value="{{ old('street') }}" />
                        @error('street')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="house">Будинок</label>
                        <input id="house" name="house" type="text" value="{{ old('house') }}" />
                        @error('house')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="apartment">Квартира (необов’язково)</label>
                        <input id="apartment" name="apartment" type="text" value="{{ old('apartment') }}" />
                        @error('apartment')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="notes">Коментар до замовлення</label>
                        <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <p class="muted-note">
                    Якщо обираєте самовивіз — заповнювати місто і адресу необов’язково.
                </p>

                <button class="btn" type="submit">Продовжити до оплати</button>
            </form>

            <div class="product-card">
                <h3>Підсумок</h3>
                <div class="cart-summary">
                    <p>Сума товарів: <strong>{{ number_format($total, 0, ',', ' ') }}₴</strong></p>
                    @php
                        $selectedMethod = old('shipping_method', array_values($shippingMethods)[0]);
                        $shippingCost = \App\Http\Controllers\CheckoutController::SHIPPING_RATES[$selectedMethod] ?? 0;
                    @endphp
                    <p>Доставка: <strong>{{ $selectedMethod }} — {{ number_format($shippingCost, 0, ',', ' ') }}₴</strong></p>
                    @if (old('city'))
                        <p>Місто: <strong>{{ old('city') }}</strong></p>
                    @endif
                    @if (old('street') || old('house'))
                        <p>Адреса: <strong>{{ trim(implode(', ', array_filter([old('street'), old('house')]))) }}</strong></p>
                    @endif
                    <p class="muted-note">Разом: <strong>{{ number_format($total + $shippingCost, 0, ',', ' ') }}₴</strong></p>
                </div>
            </div>
        </div>
    </section>
@endsection
