<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'coupon_id'];

    // ─── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with(['product', 'variant.size', 'variant.color']);
    }

    // ─── Accessors ────────────────────────────────────────────────────────

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->line_total);
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getDiscountAmountAttribute(): float
    {
        if (!$this->coupon || !$this->coupon->is_valid) return 0;
        return $this->coupon->calculateDiscount($this->subtotal);
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal - $this->discount_amount);
    }

    public function getIsEmptyAttribute(): bool
    {
        return $this->items->isEmpty();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    public function hasCoupon(): bool
    {
        return !is_null($this->coupon_id) && $this->coupon?->is_valid;
    }
}
