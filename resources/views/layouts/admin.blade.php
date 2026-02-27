<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechnoDim · @yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="app-shell">
        <div class="admin-toolbar">
        <div class="container admin-toolbar__inner">
            <a href="{{ route('home') }}" class="admin-toolbar__link">Перейти на сайт</a>
            <a href="{{ route('catalog') }}" class="admin-toolbar__link admin-toolbar__link--muted">Каталог</a>
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Перемкнути тему">Темна / Світла</button>
        </div>
        </div>
        <div class="admin-panel">
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Панель</a></li>
                    <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Категорії</a></li>
                    <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Товари</a></li>
                    <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Замовлення</a></li>
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Користувачі</a></li>
                    <li><a href="{{ route('admin.support.index') }}" class="{{ request()->routeIs('admin.support.*') ? 'active' : '' }}">Чат підтримки</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">Відгуки</a></li>
                </ul>
            </nav>

            <section class="admin-content">
                @if (session('status'))
                    <div class="flash-message">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="flash-message flash-message--error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </div>
    </div>
</body>

</html>
