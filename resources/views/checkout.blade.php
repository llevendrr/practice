@extends('layouts.app')

@section('title', __('checkout.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('checkout.title') }}</h2>
            <p>{{ __('checkout.subtitle') }}</p>
        </div>

        <div class="section-grid">
            <form class="product-card" action="{{ route('checkout.store') }}" method="post">
                @csrf
                <div class="form-grid">
                    <div class="field-group">
                        <label for="name">{{ __('checkout.name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" />
                        @error('name')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="email">{{ __('checkout.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" />
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="phone">{{ __('checkout.phone') }}</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', auth()->user()?->phone) }}" />
                        @error('phone')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="shipping_method">{{ __('checkout.shipping') }}</label>
                        <select id="shipping_method" name="shipping_method">
                            @foreach ($shippingMethods as $methodCode => $methodData)
                                <option
                                    value="{{ $methodCode }}"
                                    data-label="{{ $methodData['label'] }}"
                                    data-rate="{{ $methodData['rate'] }}"
                                    {{ old('shipping_method', 'nova_poshta') === $methodCode ? 'selected' : '' }}
                                >
                                    {{ $methodData['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_method')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group field-group--wide">
                        <label for="city">{{ __('checkout.city') }}</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}" />
                        @error('city')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="postal_code">{{ __('checkout.postal_code') }}</label>
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
                        <label for="street">{{ __('checkout.street') }}</label>
                        <input id="street" name="street" type="text" value="{{ old('street') }}" />
                        @error('street')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="house">{{ __('checkout.house') }}</label>
                        <input id="house" name="house" type="text" value="{{ old('house') }}" />
                        @error('house')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="apartment">{{ __('checkout.apartment') }}</label>
                        <input id="apartment" name="apartment" type="text" value="{{ old('apartment') }}" />
                        @error('apartment')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="notes">{{ __('checkout.notes') }}</label>
                        <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <p class="muted-note">
                    {{ __('checkout.pickup_note') }}
                </p>

                <button class="btn" type="submit">{{ __('checkout.submit') }}</button>
            </form>

            <div class="product-card">
                <h3>{{ __('checkout.summary') }}</h3>
                <div class="cart-summary" id="checkout-summary" data-subtotal="{{ $total }}">
                    <p>{{ __('checkout.products_total') }}: <strong>{{ number_format($total, 0, ',', ' ') }}&#8372;</strong></p>
                    @php
                        $selectedMethodCode = old('shipping_method', 'nova_poshta');
                        $selectedMethod = $shippingMethods[$selectedMethodCode] ?? $shippingMethods['nova_poshta'];
                        $shippingCost = $selectedMethod['rate'];
                    @endphp
                    <p>{{ __('checkout.shipping_total') }}: <strong id="shipping-total">{{ $selectedMethod['label'] }} - {{ number_format($shippingCost, 0, ',', ' ') }}&#8372;</strong></p>
                    @if (old('city'))
                        <p>{{ __('checkout.city') }}: <strong>{{ old('city') }}</strong></p>
                    @endif
                    @if (old('street') || old('house'))
                        <p>{{ __('checkout.address') }}: <strong>{{ trim(implode(', ', array_filter([old('street'), old('house')]))) }}</strong></p>
                    @endif
                    <p class="muted-note">{{ __('checkout.total') }}: <strong id="grand-total">{{ number_format($total + $shippingCost, 0, ',', ' ') }}&#8372;</strong></p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const shippingSelect = document.getElementById('shipping_method');
            const summary = document.getElementById('checkout-summary');
            const shippingTotal = document.getElementById('shipping-total');
            const grandTotal = document.getElementById('grand-total');

            if (!shippingSelect || !summary || !shippingTotal || !grandTotal) {
                return;
            }

            const subtotal = Number(summary.dataset.subtotal) || 0;

            const formatUah = (value) => `${new Intl.NumberFormat('uk-UA').format(value)}₴`;

            const recalculateTotals = () => {
                const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
                const shippingLabel = selectedOption.dataset.label || selectedOption.textContent.trim();
                const shippingRate = Number(selectedOption.dataset.rate) || 0;
                const total = subtotal + shippingRate;

                shippingTotal.textContent = `${shippingLabel} - ${formatUah(shippingRate)}`;
                grandTotal.textContent = formatUah(total);
            };

            shippingSelect.addEventListener('change', recalculateTotals);
            recalculateTotals();
        });
    </script>
@endpush
