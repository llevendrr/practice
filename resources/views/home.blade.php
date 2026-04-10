@extends('layouts.app')

@section('title', __('home.title'))

@section('content')
    <section class="hero hero--primary">
        <div class="hero__content">
            <p class="eyebrow">{{ __('home.hero.eyebrow') }}</p>
            <h1>{{ __('home.hero.title') }}</h1>
            <p>{{ __('home.hero.description') }}</p>
            <div class="hero-actions">
                <a href="{{ route('catalog') }}" class="btn hero-action-btn">{{ __('home.hero.catalog_cta') }}</a>
                <a href="{{ route('delivery') }}" class="secondary-btn hero-delivery-btn hero-action-btn">{{ __('home.hero.delivery_cta') }}</a>
            </div>
            <div class="hero-flag">
                <span>{{ __('home.hero.flag') }}</span>
            </div>
        </div>
        <div class="hero__media">
            <div class="hero-card">
                <span class="hero-card__badge">{{ __('home.seller.badge') }}</span>
                <p>{{ __('home.seller.description') }}</p>
                <ul class="hero-card__list">
                    <li>??? {{ __('home.seller.point_1') }}</li>
                    <li>? {{ __('home.seller.point_2') }}</li>
                    <li>?? {{ __('home.seller.point_3') }}</li>
                </ul>
                <div class="hero-card__stats">
                    <span>{{ __('home.seller.stat_products') }}</span>
                    <span>{{ __('home.seller.stat_support') }}</span>
                    <span>{{ __('home.seller.stat_delivery') }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <div>
                <h2>{{ __('home.new.title') }}</h2>
                <p>{{ __('home.new.subtitle') }}</p>
            </div>
            <a href="{{ route('catalog') }}">{{ __('home.new.link') }}</a>
        </div>
        <div class="grid-cards">
            @foreach ($newProducts as $product)
                <article class="product-card">
                    @if ($product->badge)
                        <span class="badge">{{ $product->badge }}</span>
                    @endif
                    <img
                        src="{{ $product->primaryImage?->url ?? 'https://images.unsplash.com/photo-1525182008055-f88b95ff7980?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $product->name }}"
                    />
                    <div>
                        <h3>{{ $product->name }}</h3>
                        <p class="meta">{{ $product->brand }} · {{ $product->model }}</p>
                        @include('partials.product-rating', ['product' => $product])
                        <div class="price">
                            <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}?</span>
                            @if ($product->discount)
                                <del>{{ number_format($product->price, 0, ',', ' ') }}?</del>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__actions">
                        <a href="{{ route('product.show', $product) }}" class="secondary-btn">{{ __('cart.details') }}</a>
                        <form action="{{ route('cart.store', $product) }}" method="post">
                            @csrf
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                {{ $product->stock < 1 ? __('cart.out_of_stock') : __('cart.to_cart') }}
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
                <h2>{{ __('home.hits.title') }}</h2>
                <p>{{ __('home.hits.subtitle') }}</p>
            </div>
            <a href="{{ route('catalog') }}">{{ __('home.hits.link') }}</a>
        </div>
        <div class="grid-cards">
            @foreach ($hitProducts as $product)
                <article class="product-card">
                    @if ($product->badge)
                        <span class="badge">{{ $product->badge }}</span>
                    @endif
                    <img src="{{ $product->primaryImage?->url ?? 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $product->name }}" />
                    <div>
                        <h3>{{ $product->name }}</h3>
                        <p class="meta">{{ $product->brand }} · {{ $product->model }}</p>
                        <div class="price">
                            <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}?</span>
                            @if ($product->discount)
                                <del>{{ number_format($product->price, 0, ',', ' ') }}?</del>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__actions">
                        <a href="{{ route('product.show', $product) }}" class="secondary-btn">{{ __('cart.details') }}</a>
                        <form action="{{ route('cart.store', $product) }}" method="post">
                            @csrf
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                {{ $product->stock < 1 ? __('cart.out_of_stock') : __('cart.to_cart') }}
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
                <h2>{{ __('home.why.title') }}</h2>
                <p>{{ __('home.why.subtitle') }}</p>
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
