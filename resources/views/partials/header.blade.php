<div class="site-brand">
    <a href="{{ route('home') }}" class="logo-link">
        <span class="logo">TechnoDim</span>
    </a>
    <p class="logo-subtitle">{{ __('ui.brand_subtitle') }}</p>
</div>

<nav class="primary-nav" aria-label="{{ __('nav.menu') }}">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('nav.home') }}</a>
    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('nav.about') }}</a>
    <a href="{{ route('delivery') }}" class="{{ request()->routeIs('delivery') ? 'active' : '' }}">{{ __('nav.delivery') }}</a>
    <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'active' : '' }}">{{ __('nav.catalog') }}</a>
    <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart*') ? 'active' : '' }}">{{ __('nav.cart') }}</a>
    @auth
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">{{ __('nav.profile') }}</a>
    @endauth
</nav>

<div class="site-header__actions">
    <div class="language-switcher" role="group" aria-label="{{ __('ui.language.label') }}">
        @foreach (config('app.supported_locales', ['uk', 'en']) as $localeOption)
            <a href="{{ route('locale.switch', $localeOption) }}" class="lang-btn {{ app()->getLocale() === $localeOption ? 'lang-btn--active' : '' }}">
                {{ __('ui.language.' . $localeOption) }}
            </a>
        @endforeach
    </div>

    <div class="user-actions">
        @guest
            <a href="{{ route('login') }}" class="auth-button">{{ __('nav.login') }}</a>
            <a href="{{ route('register') }}" class="auth-button auth-button--primary">{{ __('nav.register') }}</a>
        @else
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="admin-link">{{ __('nav.admin') }}</a>
            @endif
            <a href="{{ route('profile') }}" class="auth-button auth-button--primary">{{ __('nav.profile') }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="auth-button">{{ __('nav.logout') }}</button>
            </form>
        @endguest
    </div>

    @php
        $cartTotal = $cartCount ?? 0;
        $cartLabel = cartItemsLabel($cartTotal);
    @endphp

    <a class="cart-pill" href="{{ route('cart') }}" aria-label="{{ __('nav.cart') }}">
        <span class="cart-pill__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M3 4h2l1.2 8.2a2 2 0 0 0 2 1.8h8.9a2 2 0 0 0 2-1.6L21 7H7.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="10" cy="19" r="1.5" fill="currentColor"/>
                <circle cx="17" cy="19" r="1.5" fill="currentColor"/>
            </svg>
        </span>
        <span>{{ $cartTotal }} {{ $cartLabel }}</span>
    </a>

    <form class="header-search" action="{{ route('catalog') }}" method="get">
        <input type="search" name="search" placeholder="{{ __('ui.search.placeholder') }}" aria-label="{{ __('ui.search.label') }}" />
        <button type="submit" aria-label="{{ __('ui.search.label') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/>
                <path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
    </form>

    <button
        type="button"
        class="theme-toggle"
        data-theme-toggle
        data-theme-light="{{ __('ui.theme.light') }}"
        data-theme-dark="{{ __('ui.theme.dark') }}"
        data-theme-aria-prefix="{{ __('ui.theme.aria_prefix') }}"
        data-theme-aria-action="{{ __('ui.theme.aria_action') }}"
        aria-label="{{ __('ui.theme.toggle') }}"
    >
        <span class="theme-toggle__icon theme-toggle__icon--sun" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 2v2.2M12 19.8V22M2 12h2.2M19.8 12H22M4.93 4.93l1.56 1.56M17.51 17.51l1.56 1.56M19.07 4.93l-1.56 1.56M6.49 17.51l-1.56 1.56" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </span>
        <span class="theme-toggle__icon theme-toggle__icon--moon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M20 14.2A8.5 8.5 0 1 1 9.8 4a7 7 0 1 0 10.2 10.2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span data-theme-toggle-label>{{ __('ui.theme.toggle') }}</span>
    </button>
</div>
