@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <p class="eyebrow">Реєстрація</p>
        <h1>Почніть з TechnoDim</h1>
        <p class="auth-subtitle">Створіть акаунт, щоб зберігати замовлення і користуватися бонусами.</p>

        <form action="{{ route('register.store') }}" method="post">
            @csrf
            <div class="form-grid">
                <div class="field-group">
                    <label for="name">Ім'я</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="phone">Телефон</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" />
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" />
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password_confirmation">Підтвердьте пароль</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" />
                    @error('password_confirmation')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button class="btn auth-btn" type="submit">Створити акаунт</button>
        </form>
    </div>
</section>
@endsection
