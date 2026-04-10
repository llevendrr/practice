@extends('layouts.app')

@section('title', __('auth.passwords.request_title'))

@section('content')
    <section class="auth-page">
        <div class="auth-card">
            <h1>{{ __('auth.passwords.request_heading') }}</h1>
            <p class="auth-subtitle">
                {{ __('auth.passwords.request_subtitle') }}
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
                    <p class="muted-note">{{ __('auth.passwords.request_note') }}</p>
                </div>

                <button class="btn" type="submit">{{ __('auth.passwords.request_submit') }}</button>
            </form>

            <p class="muted-note">
                {{ __('auth.passwords.request_footer') }}
            </p>
        </div>
    </section>
@endsection
