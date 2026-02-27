@php
    $ratingValue = (float) ($product->approved_rating_avg ?? $product->average_rating);
    $ratingValue = max(0, min($ratingValue, 5));
    $roundedRating = round($ratingValue * 2) / 2;
    $fullStars = (int) floor($roundedRating);
    $hasHalfStar = ($roundedRating - $fullStars) === 0.5;
    $ratingCount = $product->approved_reviews_count ?? $product->reviews()->approved()->count();
    $showRatingMeta = $ratingCount > 0;
@endphp

<div class="product-rating {{ $showRatingMeta ? '' : 'product-rating--empty' }}">
    <div class="rating-stars" aria-label="Рейтинг товару">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $fullStars)
                <span class="star star--full" aria-hidden="true">★</span>
            @elseif ($hasHalfStar && $i === $fullStars + 1)
                <span class="star star--half" aria-hidden="true">★</span>
            @else
                <span class="star star--empty" aria-hidden="true">☆</span>
            @endif
        @endfor
    </div>

    <p class="muted-note rating-meta">
        @if ($showRatingMeta)
            ({{ number_format($ratingValue, 1, ',', '.') }}) · {{ $ratingCount }} відгуків
        @else
            Немає відгуків
        @endif
    </p>
</div>
