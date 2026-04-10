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
                <a href="{{ route('home') }}" class="admin-toolbar__link">{{ __('admin.toolbar.to_site') }}</a>
                <a href="{{ route('catalog') }}" class="admin-toolbar__link admin-toolbar__link--muted">{{ __('admin.toolbar.catalog') }}</a>

                <div class="language-switcher" role="group" aria-label="{{ __('ui.language.label') }}">
                    @foreach (config('app.supported_locales', ['uk', 'en']) as $localeOption)
                        <form action="{{ route('locale.switch', $localeOption) }}" method="post">
                            @csrf
                            <button type="submit" class="lang-btn {{ app()->getLocale() === $localeOption ? 'lang-btn--active' : '' }}">
                                {{ __('ui.language.' . $localeOption) }}
                            </button>
                        </form>
                    @endforeach
                </div>

                <button type="button" class="theme-toggle" data-theme-toggle aria-label="{{ __('ui.theme.toggle') }}">{{ __('ui.theme.toggle') }}</button>
            </div>
        </div>

        <div class="admin-panel">
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('admin.menu.dashboard') }}</a></li>
                    <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">{{ __('admin.menu.categories') }}</a></li>
                    <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">{{ __('admin.menu.products') }}</a></li>
                    <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">{{ __('admin.menu.orders') }}</a></li>
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">{{ __('admin.menu.users') }}</a></li>
                    <li><a href="{{ route('admin.support.index') }}" class="{{ request()->routeIs('admin.support.*') ? 'active' : '' }}">{{ __('admin.menu.support') }}</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">{{ __('admin.menu.reviews') }}</a></li>
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

