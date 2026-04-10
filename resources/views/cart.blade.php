@extends('layouts.app')

@section('title', __('cart.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>{{ __('cart.title') }}</h2>
            <p>{{ __('cart.subtitle') }}</p>
        </div>

        @if ($items->isEmpty())
            <p>{{ __('cart.empty') }}</p>
        @else
            <div class="table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>{{ __('cart.columns.product') }}</th>
                            <th>{{ __('cart.columns.price') }}</th>
                            <th>{{ __('cart.columns.quantity') }}</th>
                            <th>{{ __('cart.columns.sum') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['product']->name }}</strong><br>
                                    {{ $item['product']->brand }} · {{ $item['product']->model }}
                                </td>
                                <td>{{ number_format($item['product']->discounted_price, 0, ',', ' ') }}?</td>
                                <td>
                                    <form action="{{ route('cart.update', $item['product']) }}" method="post" class="table-actions">
                                        @csrf
                                        @method('patch')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" style="width:80px;" />
                                        <button class="secondary-btn" type="submit">{{ __('cart.update') }}</button>
                                    </form>
                                </td>
                                <td>{{ number_format($item['subtotal'], 0, ',', ' ') }}?</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['product']) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="secondary-btn" type="submit">{{ __('cart.remove') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary">
                <p>{{ __('cart.total') }}: <strong>{{ number_format($total, 0, ',', ' ') }}?</strong></p>
                @auth
                    <a href="{{ route('checkout.index') }}" class="btn">{{ __('cart.checkout') }}</a>
                @else
                    <p>{{ __('cart.login_for_checkout') }}</p>
                @endauth
            </div>
        @endif
    </section>
@endsection
