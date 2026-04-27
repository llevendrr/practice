@extends('layouts.app')

@section('title', __('catalog.title'))

@section('content')
<section class="section catalog-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>{{ __('catalog.heading') }}</h2>
                <p>{{ __('catalog.subtitle') }}</p>
            </div>
        </div>

        <div class="catalog-wrapper">
            <aside class="catalog-filter">
                <h3 class="filter-heading">{{ __('catalog.filters.title') }}</h3>
                <form action="{{ route('catalog') }}" method="get">
                    <div class="field-group">
                        <label for="search">{{ __('catalog.filters.search') }}</label>
                        <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="{{ __('catalog.filters.search_placeholder') }}" />
                    </div>

                    <div class="field-group">
                        <label for="category">{{ __('catalog.filters.category') }}</label>
                        <select name="category" id="category">
                            <option value="">{{ __('catalog.filters.all_categories') }}</option>
                            @foreach ($filters['categories'] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="brand">{{ __('catalog.filters.brand') }}</label>
                        <select name="brand" id="brand">
                            <option value="">{{ __('catalog.filters.all_brands') }}</option>
                            @foreach ($filters['brands'] as $brand)
                                <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label>{{ __('catalog.filters.price') }}</label>
                        <div class="range-pair">
                            <input name="price_min" type="number" min="0" placeholder="{{ __('catalog.filters.from') }}" value="{{ request('price_min') }}" />
                            <input name="price_max" type="number" min="0" placeholder="{{ __('catalog.filters.to') }}" value="{{ request('price_max') }}" />
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="stock">{{ __('catalog.filters.stock') }}</label>
                        <select name="stock" id="stock">
                            <option value="">{{ __('catalog.filters.all_items') }}</option>
                            <option value="1" {{ request('stock') ? 'selected' : '' }}>{{ __('catalog.filters.in_stock_only') }}</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="sort">{{ __('catalog.filters.sort') }}</label>
                        <select name="sort" id="sort">
                            <option value="">{{ __('catalog.filters.random') }}</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('catalog.filters.newest') }}</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('catalog.filters.popular') }}</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ __('catalog.filters.price_asc') }}</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('catalog.filters.price_desc') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn">{{ __('catalog.filters.submit') }}</button>
                </form>
            </aside>

            <div class="catalog-products">
                <div class="catalog-grid">
                    @forelse ($products as $product)
                        <article class="product-card">
                            @if ($product->badge)
                                <span class="badge">{{ $product->badge }}</span>
                            @endif
                            <img
                                src="{{ $product->primaryImage?->url ?? 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80' }}"
                                alt="{{ $product->name }}"
                            />
                            <div>
                                <h3>{{ $product->name }}</h3>
                                <p class="meta">{{ $product->brand }} · {{ $product->model }}</p>
                                @include('partials.product-rating', ['product' => $product])
                                <div class="price">
                                    <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}&#8372;</span>
                                    @if ($product->discount)
                                        <del>{{ number_format($product->price, 0, ',', ' ') }}&#8372;</del>
                                    @endif
                                </div>
                            </div>

                            <div class="product-card__actions">
                                <a href="{{ route('product.show', $product) }}" class="secondary-btn">{{ __('cart.details') }}</a>
                                <form action="{{ route('cart.store', $product) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1" />
                                    <button type="submit" class="cart-btn" @if ($product->stock < 1) disabled @endif>
                                        {{ $product->stock < 1 ? __('cart.out_of_stock') : __('cart.to_cart') }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="product-card">
                            <p>{{ __('catalog.empty') }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="pagination-wrapper">
                    {{ $products->withQueryString()->links('vendor.pagination.techno') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
