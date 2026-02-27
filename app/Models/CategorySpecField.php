<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategorySpecField extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'label',
        'key',
        'field_type',
        'required',
        'order',
        'options',
    ];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
