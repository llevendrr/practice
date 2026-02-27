@extends('layouts.app')

@section('title', 'Кошик')

@section('content')
    <section class="section">
        <div class="section-heading">
            <h2>Кошик</h2>
            <p>Перевірте товари перед оплатою.</p>
        </div>

        @if ($items->isEmpty())
            <p>У вас ще немає товарів у кошику.</p>
        @else
            <div class="table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Ціна</th>
                            <th>Кількість</th>
                            <th>Сума</th>
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
                                <td>{{ number_format($item['product']->discounted_price, 0, ',', ' ') }}₴</td>
                                <td>
                                    <form action="{{ route('cart.update', $item['product']) }}" method="post" class="table-actions">
                                        @csrf
                                        @method('patch')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" style="width:80px;" />
                                        <button class="secondary-btn" type="submit">Оновити</button>
                                    </form>
                                </td>
                                <td>{{ number_format($item['subtotal'], 0, ',', ' ') }}₴</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['product']) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="secondary-btn" type="submit">Видалити</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary">
                <p>Разом: <strong>{{ number_format($total, 0, ',', ' ') }}₴</strong></p>
                @auth
                    <a href="{{ route('checkout.index') }}" class="btn">Оформити замовлення</a>
                @else
                    <p>Увійдіть, щоб перейти до оформлення.</p>
                @endauth
            </div>
        @endif
    </section>
@endsection
