<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'category_id',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'value'                 => 'decimal:2',
        'min_order_amount'      => 'decimal:2',
        'max_discount'          => 'decimal:2',
        'is_active'             => 'boolean',
        'starts_at'             => 'datetime',
        'expires_at'            => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function getIsValidAttribute(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_amount) return 0;

        $discount = $this->type === 'percentage'
            ? ($subtotal * $this->value / 100)
            : $this->value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return round(min($discount, $subtotal), 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
