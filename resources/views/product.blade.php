@extends('layouts.app')

@section('title', $product->name)

@section('content')
    @php
        $specDefinitions = $specFields ?? collect();
    @endphp
    <section class="section">
        <div class="product-detail">
            <div class="product-detail__media">
                <div class="product-gallery">
                    <div class="gallery-main">
                        <img src="{{ $product->primary_image?->url ?? 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $product->name }}" />
                    </div>
                    <div class="gallery-thumbs">
                        @foreach ($product->images as $image)
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="{{ $loop->first ? 'active' : '' }}" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="product-detail__info">
                <p class="eyebrow">TechnoDim · {{ $product->brand }}</p>
                <h1>{{ $product->name }}</h1>
                <p class="meta">{{ $product->model }}</p>
                <div class="price">
                    <span>{{ number_format($product->discounted_price, 0, ',', ' ') }}₴</span>
                    @if ($product->discount)
                        <del>{{ number_format($product->price, 0, ',', ' ') }}₴</del>
                    @endif
                </div>
                <p class="product-detail__description">{{ $product->description }}</p>
                <div class="product-meta">
                    <span class="badge badge--muted">{{ $product->stock > 0 ? 'Є в наявності' : 'Під замовлення' }}</span>
                    <span class="product-meta__label">Популярність: {{ $product->popularity }}</span>
                </div>
                <form action="{{ route('cart.store', $product) }}" method="post" class="product-detail__form">
                    @csrf
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="quantity">Кількість</label>
                            <input type="number" id="quantity" name="quantity" min="1" max="{{ max(1, $product->stock) }}" value="1" />
                        </div>
                    </div>
                    <button type="submit" class="btn" {{ $product->stock < 1 ? 'disabled' : '' }}>Додати в кошик</button>
                </form>
            </div>
        </div>

        <div class="product-detail__extras">
                <div class="feature-card">
                    <h3>Характеристики</h3>
                    <table class="cart-table">
                        <tbody>
                            @foreach ($product->specifications ?? [] as $key => $value)
                                @php
                                    $definition = $specDefinitions[$key] ?? null;
                                    $label = $definition?->label ??
                                        \Illuminate\Support\Str::of($key)->replace('_', ' ')->title();
                                @endphp
                                <tr>
                                    <th>{{ $label }}</th>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <div class="feature-card">
                <h3>Відгуки</h3>
                <p class="stars">Середній рейтинг: {{ number_format($product->average_rating, 1) }} / 5</p>
                <div class="reviews">
                    @forelse ($product->reviews as $review)
                        <div class="review">
                            <strong>{{ $review->user->name }}</strong>
                            <div class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                            <p>{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p>Поки що нема відгуків.</p>
                    @endforelse
                </div>

                @auth
                    <form action="{{ route('reviews.store', $product) }}" method="post">
                        @csrf
                        <div class="form-grid">
                            <div class="field-group">
                                <label for="rating">Рейтинг (1-5)</label>
                                <input type="number" min="1" max="5" id="rating" name="rating" value="{{ old('rating', 5) }}" />
                            </div>
                            <div class="field-group">
                                <label for="comment">Відгук</label>
                                <textarea id="comment" name="comment">{{ old('comment') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn">Додати відгук</button>
                    </form>
                @else
                    <p>Увійдіть, щоб залишити відгук.</p>
                @endauth
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <h2>Схожі товари</h2>
        </div>
        <div class="grid-cards">
            @foreach ($related as $item)
                <article class="product-card">
                    @if ($item->badge)
                        <span class="badge">{{ $item->badge }}</span>
                    @endif
                    <img
                        src="{{ $item->primary_image?->url ?? 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $item->name }}"
                    />
                    <div>
                        <h3>{{ $item->name }}</h3>
                        <p class="meta">{{ $item->brand }}</p>
                        <div class="price">
                            <span>{{ number_format($item->discounted_price, 0, ',', ' ') }}₴</span>
                            @if ($item->discount)
                                <del>{{ number_format($item->price, 0, ',', ' ') }}₴</del>
                            @endif
                        </div>
                    </div>
                    <div class="product-card__actions">
                        <a href="{{ route('product.show', $item) }}" class="secondary-btn">Детальніше</a>
                        <form action="{{ route('cart.store', $item) }}" method="post">
                            @csrf
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="cart-btn" {{ $item->stock < 1 ? 'disabled' : '' }}>
                                {{ $item->stock < 1 ? 'Немає в наявності' : 'У кошик' }}
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
