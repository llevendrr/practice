<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'model',
        'price',
        'discount',
        'stock',
        'description',
        'specifications',
        'popularity',
        'is_new',
        'is_hit',
        'is_active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_new' => 'boolean',
        'is_hit' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->select([
                'id',
                'product_id',
                'filename',
                'mime_type',
                'is_primary',
                'sort_order',
                'created_at',
                'updated_at',
            ])
            ->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->select([
                'id',
                'product_id',
                'filename',
                'mime_type',
                'is_primary',
                'sort_order',
                'created_at',
                'updated_at',
            ])
            ->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeWithApprovedRatings(Builder $query): Builder
    {
        return $query
            ->withAvg(['reviews as approved_rating_avg' => fn ($relation) => $relation->approved()], 'rating')
            ->withCount(['reviews as approved_reviews_count' => fn ($relation) => $relation->approved()]);
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDiscountedPriceAttribute(): float
    {
        $price = (float) $this->price;
        $discount = (float) $this->discount;
        return $discount > 0 ? max($price - $discount, 0) : $price;
    }

    public function getBadgeAttribute(): ?string
    {
        if ($this->discount > 0) {
            return 'Знижка';
        }

        if ($this->is_hit) {
            return 'Хіт';
        }

        if ($this->is_new) {
            return 'Новинка';
        }

        return null;
    }

    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->first(fn ($image) => $image->is_primary) ?? $this->images->first();
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) $this->reviews()->approved()->avg('rating') ?: 0;
    }
}
