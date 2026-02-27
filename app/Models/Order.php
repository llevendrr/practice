<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PAID = 'paid';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';

    public const STATUS_LABELS = [
        self::STATUS_NEW => 'Нове',
        self::STATUS_PROCESSING => 'В обробці',
        self::STATUS_SHIPPED => 'Відправлено',
        self::STATUS_DONE => 'Завершено',
        self::STATUS_CANCELED => 'Скасовано',
        self::STATUS_PAID => 'Оплачено',
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'name',
        'email',
        'phone',
        'shipping_method',
        'shipping_city',
        'shipping_street',
        'shipping_house',
        'shipping_apartment',
        'shipping_cost',
        'total',
        'status',
        'payment_status',
        'notes',
        'postal_code',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeForStatus($query, string $status): void
    {
        $query->where('status', $status);
    }

    public function cancelable(): bool
    {
        return ! in_array($this->status, [self::STATUS_SHIPPED, self::STATUS_DONE, self::STATUS_PAID], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabel($this->status);
    }

    public static function statusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? ucfirst($status);
    }

    public static function statusLabels(): array
    {
        return self::STATUS_LABELS;
    }
}






