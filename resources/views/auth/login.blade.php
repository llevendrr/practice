@extends('layouts.app')

@section('title', __('auth.login.title'))

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <p class="eyebrow">{{ __('auth.login.eyebrow') }}</p>
        <h1>{{ __('auth.login.heading') }}</h1>
        <p class="auth-subtitle">{{ __('auth.login.subtitle') }}</p>

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
                    <label for="password">{{ __('auth.login.password') }}</label>
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
                    {{ __('auth.login.remember') }}
                </label>
                <a href="{{ route('password.request') }}">{{ __('auth.login.forgot') }}</a>
            </div>

            <button class="btn auth-btn" type="submit">{{ __('auth.login.submit') }}</button>
        </form>
    </div>
</section>
@endsection
