@extends('layouts.app')

@section('title', 'Відновлення пароля')

@section('content')
    <section class="auth-page">
        <div class="auth-card">
            <h1>Відновлення пароля</h1>
            <p class="auth-subtitle">
                Вкажіть email, який ви використовуєте в TechnoDim, і ми надішлемо безпечне посилання для зміни пароля.
            </p>

            <form method="post" action="{{ route('password.email') }}">
                @csrf

                <div class="form-grid">
                    <div class="field-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <p class="muted-note">Ми надішлемо посилання, яке дійсне протягом 60 хвилин.</p>
                </div>

                <button class="btn" type="submit">Надіслати лист</button>
            </form>

            <p class="muted-note">
                Перевіряйте папку «Спам», а якщо лист не прийшов — спробуйте ще раз або напишіть до підтримки через форму.
            </p>
        </div>
    </section>
@endsection
