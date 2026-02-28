<div class="site-brand">
    <a href="{{ route('home') }}" class="logo-link">
        <span class="logo">TechnoDim</span>
    </a>
    <p class="logo-subtitle">Сучасна техніка з гарантією</p>
</div>

<nav class="primary-nav" aria-label="Основне меню">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Головна</a>
    <a href="{{ route('about') }}">Про нас</a>
    <a href="{{ route('delivery') }}">Доставка</a>
    <a href="{{ route('catalog') }}">Каталог</a>
    <a href="{{ route('cart') }}">Кошик</a>
    @auth
        <a href="{{ route('profile') }}">Профіль</a>
    @endauth
</nav>

<div class="site-header__actions">
    <div class="user-actions">
        @guest
            <a href="{{ route('login') }}" class="auth-button">Вхід</a>
            <a href="{{ route('register') }}" class="auth-button auth-button--primary">Реєстрація</a>
        @else
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="admin-link">Адмінка</a>
            @endif
            <a href="{{ route('profile') }}" class="auth-button auth-button--primary">Профіль</a>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="auth-button">Вийти</button>
            </form>
        @endguest
    </div>

    @php
        $cartTotal = $cartCount ?? 0;
        $cartLabel = ukrainianPluralForm($cartTotal);
    @endphp

    <a class="cart-pill" href="{{ route('cart') }}" aria-label="Кошик">
        <span class="cart-pill__icon">🛒</span>
        <span>{{ $cartTotal }} {{ $cartLabel }}</span>
    </a>

    <form class="header-search" action="{{ route('catalog') }}" method="get">
        <input type="search" name="search" placeholder="Пошук техніки, бренду або моделі" aria-label="Пошук" />
        <button type="submit" aria-label="Пошук">🔍</button>
    </form>

    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Перемкнути тему">Темна / Світла</button>
</div>
