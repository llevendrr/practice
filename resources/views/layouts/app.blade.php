<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('home.hero.description') }}">
    <title>{{ config('app.name') }} · @yield('title', __('home.title')) </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="site-header">
        <div class="container site-header__inner">
            @include('partials.header')
        </div>
    </header>

    <main class="page-content">
        <div class="container">
            @if (session('status'))
                <div class="flash-message">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="flash-message flash-message--error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="site-footer" id="delivery">
        <div class="container">
            @include('partials.footer')
        </div>
    </footer>
    @stack('scripts')
</body>

</html>
