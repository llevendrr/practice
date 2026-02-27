@extends('layouts.app')

@section('title', 'Вхід')

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <p class="eyebrow">Вхід до акаунту</p>
        <h1>Ласкаво просимо</h1>
        <p class="auth-subtitle">Авторизуйтесь, щоб переглянути історію замовлень і персональні пропозиції.</p>

        <form action="{{ route('login.attempt') }}" method="post">
            @csrf
            <div class="form-grid">
                <div class="field-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="current-password" />
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                    @error('credentials')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                    Запам’ятати мене
                </label>
                <a href="{{ route('password.request') }}">Забули пароль?</a>
            </div>

            <button class="btn auth-btn" type="submit">Увійти</button>
        </form>
    </div>
</section>
@endsection
