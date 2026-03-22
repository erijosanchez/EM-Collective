<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'image_mobile',
        'button_text',
        'button_url',
        'position',
        'target_category_slug',
        'bg_color',
        'text_color',
        'text_align',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    public function scopeForPosition($query, string $position)
    {
        return $query->where('position', $position)->orderBy('sort_order');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    public function getIsCurrentlyActiveAttribute(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->ends_at && now()->gt($this->ends_at)) return false;
        return true;
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getImageMobileUrlAttribute(): ?string
    {
        return $this->image_mobile ? asset('storage/' . $this->image_mobile) : null;
    }
}
