@extends('layouts.app')

@section('title', 'Каталог')

@section('content')
<section class="section catalog-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <h2>Каталог техніки</h2>
                <p>Фільтруйте за брендом, категорією чи ціною, щоб підібрати новинку.</p>
            </div>
        </div>

        <div class="catalog-wrapper">
            <aside class="catalog-filter">
                <h3 class="filter-heading">Фільтр</h3>
                <form action="{{ route('catalog') }}" method="get">
                    <div class="field-group">
                        <label for="search">Пошук</label>
                        <input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Наприклад, iPhone 15" />
                    </div>

                    <div class="field-group">
                        <label for="category">Категорія</label>
                        <select name="category" id="category">
                            <option value="">Усі категорії</option>
                            @foreach ($filters['categories'] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="brand">Бренд</label>
                        <select name="brand" id="brand">
                            <option value="">Усі бренди</option>
                            @foreach ($filters['brands'] as $brand)
                                <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label>Ціна, ₴</label>
                        <div class="range-pair">
                            <input name="price_min" type="number" min="0" placeholder="від" value="{{ request('price_min') }}" />
                            <input name="price_max" type="number" min="0" placeholder="до" value="{{ request('price_max') }}" />
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="stock">Наявність</label>
                        <select name="stock" id="stock">
                            <option value="">Всі товари</option>
                            <option value="1" {{ request('stock') ? 'selected' : '' }}>Тільки зі складу</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="sort">Сортування</label>
                        <select name="sort" id="sort">
                            <option value="">За новинками</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Популярні</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Ціна ↑</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Ціна ↓</option>
                        </select>
                    </div>

                    <button type="submit" class="btn">Показати товари</button>
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
                                    <button type="submit" class="cart-btn" @if ($product->stock < 1) disabled @endif>
                                        {{ $product->stock < 1 ? 'Немає в наявності' : 'У кошик' }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="product-card">
                            <p>Нічого не знайдено за цими параметрами.</p>
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
