@extends('layouts.app')

@section('title', __('auth.passwords.reset_title'))

@section('content')
    <section class="auth-page">
        <div class="auth-card">
            <h1>{{ __('auth.passwords.reset_heading') }}</h1>
            <p class="auth-subtitle">
                {{ __('auth.passwords.reset_subtitle') }}
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
                        <label for="password">{{ __('auth.passwords.new_password') }}</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required />
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="password_confirmation">{{ __('auth.passwords.confirm_password') }}</label>
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
                    <p class="muted-note">{{ __('auth.passwords.reset_note') }}</p>
                </div>

                <button class="btn" type="submit">{{ __('auth.passwords.reset_submit') }}</button>
            </form>
        </div>
    </section>
@endsection
