@extends('layouts.app')

@section('title', __('auth.register.title'))

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <p class="eyebrow">{{ __('auth.register.eyebrow') }}</p>
        <h1>{{ __('auth.register.heading') }}</h1>
        <p class="auth-subtitle">{{ __('auth.register.subtitle') }}</p>

        <form action="{{ route('register.store') }}" method="post">
            @csrf
            <div class="form-grid">
                <div class="field-group">
                    <label for="name">{{ __('auth.register.name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="email">{{ __('auth.register.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="phone">{{ __('auth.register.phone') }}</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" />
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password">{{ __('auth.register.password') }}</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" />
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password_confirmation">{{ __('auth.register.password_confirmation') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" />
                    @error('password_confirmation')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button class="btn auth-btn" type="submit">{{ __('auth.register.submit') }}</button>
        </form>
    </div>
</section>
@endsection
