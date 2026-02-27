@extends('layouts.app')

@section('title', 'Мій профіль')

@section('content')
    @include('account.partials.tabs')

    <section class="section profile-page">
        <div class="profile-grid">
            <div class="profile-card">
                <h2>Особисті дані</h2>
                <form action="{{ route('profile.update') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="name">Ім’я</label>
                            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required />
                            @error('name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required />
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn auth-btn">Зберегти</button>
                </form>
            </div>

            <div class="security-card">
                <h3>Налаштування безпеки</h3>
                <form action="{{ route('profile.password') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="current_password">Старий пароль</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required />
                            @error('current_password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="password">Новий пароль</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required />
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="password_confirmation">Підтвердіть пароль</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required />
                        </div>
                    </div>
                    <button type="submit" class="btn auth-btn">Змінити пароль</button>
                </form>
                <div class="security-card__helper">
                    <a class="link-button" href="{{ route('password.request') }}">Забули пароль? Надіслати лист</a>
                </div>
            </div>
        </div>

        <div class="order-history">
            <div class="section-heading">
                <div>
                    <h2>Історія замовлень</h2>
                    <p>Останні замовлення та актуальний статус.</p>
                </div>
                <a href="{{ route('orders') }}">Усі замовлення →</a>
            </div>
            <div class="order-history__outer">
                @if ($orders->isEmpty())
                    <p>Поки що немає замовлень.</p>
                @else
                    <div class="table-wrap">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>№ замовлення</th>
                                    <th>Статус</th>
                                    <th>Оплата</th>
                                    <th>Сума</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->status_label }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>{{ number_format($order->total, 0, ',', ' ') }}₴</td>
                                        <td class="order-table__actions">
                                            @if ($order->payment_status === \App\Models\Order::PAYMENT_PENDING)
                                                <a class="secondary-btn" href="{{ route('payment.select', $order) }}">Оплатити</a>
                                            @else
                                                <span class="muted-note">завершено</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
