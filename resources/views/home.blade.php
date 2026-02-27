@extends('layouts.app')

@section('title', 'Головна')

@section('content')
    <section class="hero hero--primary">
        <div class="hero__content">
            <p class="eyebrow">TechnoDim · інтернет-магазин техніки з душею</p>
            <h1>Готові доставити офіційні гаджети у кожне місто України</h1>
            <p>Офіційна гарантія, експертні консультації та швидка доставка — все, що вам потрібно, щоб купити техніку без компромісів.</p>
            <div class="hero-actions">
                <a href="{{ route('catalog') }}" class="btn hero-action-btn">Переглянути каталог</a>
                <a href="{{ route('delivery') }}" class="secondary-btn hero-delivery-btn hero-action-btn">Доставка та оплата</a>
            </div>
            <div class="hero-flag">
                <span>Гарантія 24 місяці · Техпідтримка 24/7 · Безпечні повернення</span>
            </div>
        </div>
        <div class="hero__media">
            <div class="hero-card">
                <span class="hero-card__badge">Офіційний продавець</span>
                <p>Закуповуємо техніку напряму від брендів, тому гарантуємо сертифіковані моделі з повною сервісною підтримкою.</p>
                <ul class="hero-card__list">
                    <li>🛡️ Гарантія до 24 місяців</li>
                    <li>⚡ Доставка наступного дня по Україні</li>
                    <li>💬 Підтримка 24/7 у чаті та на телефоні</li>
                </ul>
                <div class="hero-card__stats">
                    <span>1000+ товарів</span>
                    <span>24/7 підтримка</span>
                    <span>99,8% доставлено вчасно</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <div>
                <h2>Новинки</h2>
                <p>Партії перевірені, гарантія — офіційна, опис — чесний.</p>
            </div>
            <a href="{{ route('catalog') }}">Всі новинки →</a>
        </div>
        <div class="grid-cards">
            @foreach ($newProducts as $product)
                <article class="product-card">
                    @if ($product->badge)
                        <span class="badge">{{ $product->badge }}</span>
                    @endif
                    <img
                        src="{{ $product->primary_image?->url ?? 'https://images.unsplash.com/photo-1525182008055-f88b95ff7980?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $product->name }}"
                    />
                    <div>
                        <h3>{{ $product->name }}</h3>
                        <p class="meta">{{ $product->brand }} · {{ $product->model }}</p>
                        @include('partials.product-rating', ['product' => $product])
                        @include('partials.product-rating', ['product' => $product])
                        <div class="price">
                            <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}₴</span>
                            @if ($product->discount)
                                <del>{{ number_format($product->price, 0, ',', ' ') }}₴</del>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__actions">
                        <a href="{{ route('product.show', $product) }}" class="secondary-btn">Детальніше</a>
                        <form action="{{ route('cart.store', $product) }}" method="post">
                            @csrf
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                {{ $product->stock < 1 ? 'Немає в наявності' : 'У кошик' }}
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <div>
                <h2>Хіти продажів</h2>
                <p>Вибір, який любить спільнота — ми підбираємо лише перевірені позиції.</p>
            </div>
            <a href="{{ route('catalog') }}">Детальніше →</a>
        </div>
        <div class="grid-cards">
            @foreach ($hitProducts as $product)
                <article class="product-card">
                    @if ($product->badge)
                        <span class="badge">{{ $product->badge }}</span>
                    @endif
                    <img src="{{ $product->primary_image?->url ?? 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $product->name }}" />
                    <div>
                        <h3>{{ $product->name }}</h3>
                        <p class="meta">{{ $product->brand }} · {{ $product->model }}</p>
                        <div class="price">
                            <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}₴</span>
                            @if ($product->discount)
                                <del>{{ number_format($product->price, 0, ',', ' ') }}₴</del>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__actions">
                        <a href="{{ route('product.show', $product) }}" class="secondary-btn">Детальніше</a>
                        <form action="{{ route('cart.store', $product) }}" method="post">
                            @csrf
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                {{ $product->stock < 1 ? 'Немає в наявності' : 'У кошик' }}
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="section section--highlighted">
        <div class="section-heading">
            <div>
                <h2>Чому TechnoDim</h2>
                <p>Потужна техніка, що підкріплена підтримкою та чесною командою.</p>
            </div>
        </div>
        <div class="why-grid">
            @foreach ($whyHighlights as $highlight)
                <article class="feature-card why-card">
                    <div class="why-card__icon">{{ $highlight['icon'] }}</div>
                    <h3>{{ $highlight['title'] }}</h3>
                    <p>{{ $highlight['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
