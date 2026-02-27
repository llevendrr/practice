<div class="account-tabs">
    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Профіль</a>
    <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}">Замовлення</a>
    <a href="{{ route('support.index') }}" class="{{ request()->routeIs('support.*') ? 'active' : '' }}">Підтримка</a>
</div>
