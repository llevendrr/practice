@extends('layouts.app')

@section('title', 'Скидання пароля')

@section('content')
    <section class="auth-page">
        <div class="auth-card">
            <h1>Скидання пароля</h1>
            <p class="auth-subtitle">
                Введіть новий пароль, щоб повернути доступ до облікового запису. Пам’ятайте про складність: мінімум 8 символів.
            </p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}" />

                <div class="form-grid">
                    <div class="field-group">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $email ?? '') }}"
                            required
                            autofocus
                        />
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label for="password">Новий пароль</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required />
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="password_confirmation">Підтвердіть пароль</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                        />
                    </div>
                </div>

                @error('token')
                    <p class="error-text">{{ $message }}</p>
                @enderror

                <div class="form-actions">
                    <p class="muted-note">Після зміни пароля старий пароль перестане працювати.</p>
                </div>

                <button class="btn" type="submit">Зберегти пароль</button>
            </form>
        </div>
    </section>
@endsection
