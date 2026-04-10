<div class="site-brand">
    <a href="{{ route('home') }}" class="logo-link">
        <span class="logo">TechnoDim</span>
    </a>
    <p class="logo-subtitle">{{ __('ui.brand_subtitle') }}</p>
</div>

<nav class="primary-nav" aria-label="{{ __('ui.nav.menu') }}">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('ui.nav.home') }}</a>
    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('ui.nav.about') }}</a>
    <a href="{{ route('delivery') }}" class="{{ request()->routeIs('delivery') ? 'active' : '' }}">{{ __('ui.nav.delivery') }}</a>
    <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'active' : '' }}">{{ __('ui.nav.catalog') }}</a>
    <a href="{{ route('cart') }}" class="{{ request()->routeIs('cart*') ? 'active' : '' }}">{{ __('ui.nav.cart') }}</a>
    @auth
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">{{ __('ui.nav.profile') }}</a>
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
            <a href="{{ route('login') }}" class="auth-button">{{ __('ui.nav.login') }}</a>
            <a href="{{ route('register') }}" class="auth-button auth-button--primary">{{ __('ui.nav.register') }}</a>
        @else
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="admin-link">{{ __('ui.nav.admin') }}</a>
            @endif
            <a href="{{ route('profile') }}" class="auth-button auth-button--primary">{{ __('ui.nav.profile') }}</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="auth-button">{{ __('ui.nav.logout') }}</button>
            </form>
        @endguest
    </div>

    @php
        $cartTotal = $cartCount ?? 0;
        $cartLabel = cartItemsLabel($cartTotal);
    @endphp

    <a class="cart-pill" href="{{ route('cart') }}" aria-label="{{ __('ui.nav.cart') }}">
        <span class="cart-pill__icon">&#128722;</span>
        <span>{{ $cartTotal }} {{ $cartLabel }}</span>
    </a>

    <form class="header-search" action="{{ route('catalog') }}" method="get">
        <input type="search" name="search" placeholder="{{ __('ui.search.placeholder') }}" aria-label="{{ __('ui.search.label') }}" />
        <button type="submit" aria-label="{{ __('ui.search.label') }}">&#128269;</button>
    </form>

    <button type="button" class="theme-toggle" data-theme-toggle aria-label="{{ __('ui.theme.toggle') }}">{{ __('ui.theme.toggle') }}</button>
</div>


