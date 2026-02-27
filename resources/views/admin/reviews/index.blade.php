@extends('layouts.admin')

@section('title', 'Відгуки')

@section('content')
    <div class="admin-card">
        <div class="section-heading">
            <div>
                <h2>Керування відгуками</h2>
                <p>Фільтруйте відгуки, переглядайте коментарі клієнтів і змінюйте статус видимості.</p>
            </div>
        </div>

        <form class="form-grid" method="get" action="{{ route('admin.reviews.index') }}">
            <div class="field-group">
                <label for="product">Товар</label>
                <input id="product" name="product" type="search" value="{{ request('product') }}" placeholder="Назва або частина назви" />
            </div>

            <div class="field-group">
                <label for="user">Користувач</label>
                <input id="user" name="user" type="search" value="{{ request('user') }}" placeholder="Ім’я або email" />
            </div>

            <div class="field-group">
                <label for="rating">Рейтинг</label>
                <select id="rating" name="rating">
                    <option value="">Усі</option>
                    @foreach (range(1, 5) as $score)
                        <option value="{{ $score }}" {{ request('rating') == $score ? 'selected' : '' }}>
                            {{ $score }} ★
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="sort">Сортування</label>
                <select id="sort" name="sort">
                    <option value="newest" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>За новими</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>За старими</option>
                </select>
            </div>

            <div class="form-actions" style="justify-content:flex-end; margin-top:0;">
                <a href="{{ route('admin.reviews.index') }}" class="secondary-btn">Скинути</a>
                <button type="submit" class="btn">Показати</button>
            </div>
        </form>
    </div>

    <div class="admin-card" style="margin-top:1.5rem;">
        <div class="table-wrap">
            <table class="cart-table reviews-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Товар</th>
                        <th>Користувач</th>
                        <th>Рейтинг</th>
                        <th>Коментар</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php use Illuminate\Support\Str; @endphp
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>
                                @if ($review->product)
                                    <a href="{{ route('product.show', $review->product) }}" target="_blank">{{ $review->product->name }}</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                {{ $review->user?->name ?? 'Користувач видалений' }}
                                <br />
                                <small class="muted-note">{{ $review->user?->email ?? '—' }}</small>
                            </td>
                            <td>{{ $review->rating }}/5</td>
                            <td>
                                @if ($review->comment)
                                    <details class="review-comment">
                                        <summary>{{ Str::limit($review->comment, 80) }}</summary>
                                        <p>{{ $review->comment }}</p>
                                    </details>
                                @else
                                    <span class="muted-note">Без коментаря</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at?->format('d.m.Y H:i') }}</td>
                            <td>
                                <span class="status-badge {{ $review->approved ? '' : 'status-badge--ghost' }}">
                                    {{ $review->approved ? 'Видимий' : 'Прихований' }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <form method="post" action="{{ route('admin.reviews.toggle', $review) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" class="secondary-btn">
                                        {{ $review->approved ? 'Приховати' : 'Опублікувати' }}
                                    </button>
                                </form>
                                <form
                                    method="post"
                                    action="{{ route('admin.reviews.destroy', $review) }}"
                                    onsubmit="return confirm('Видалити відгук #{{ $review->id }}?');"
                                >
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="secondary-btn secondary-btn--danger">Видалити</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Відгуків поки немає.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $reviews->withQueryString()->links('vendor.pagination.techno') }}
        </div>
    </div>
@endsection
