<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'sku',
        'stock',
        'price_modifier',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->product->current_price + $this->price_modifier;
    }

    public function getLabelAttribute(): string
    {
        $parts = [];
        if ($this->size)  $parts[] = 'Talla ' . $this->size->name;
        if ($this->color) $parts[] = $this->color->name;
        return implode(' / ', $parts);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
