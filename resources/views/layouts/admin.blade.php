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
                        <a href="{{ route('locale.switch', $localeOption) }}" class="lang-btn {{ app()->getLocale() === $localeOption ? 'lang-btn--active' : '' }}">
                            {{ __('ui.language.' . $localeOption) }}
                        </a>
                    @endforeach
                </div>

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

