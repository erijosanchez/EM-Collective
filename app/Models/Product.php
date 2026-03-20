<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'details',
        'base_price',
        'sale_price',
        'sku',
        'gender',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'attributes',
    ];

    protected $casts = [
        'base_price'   => 'decimal:2',
        'sale_price'   => 'decimal:2',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'attributes'   => 'array',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price');
    }

    public function scopeForGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0));
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->base_price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->sale_price) return null;
        return (int) round((($this->base_price - $this->sale_price) / $this->base_price) * 100);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        return $this->images->where('is_primary', true)->first()?->path
            ?? $this->images->sortBy('sort_order')->first()?->path;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->base_price;
    }

    public function getTotalStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews->avg('rating') ?? 0, 1);
    }

    public function hasVariantInStock(?int $sizeId = null, ?int $colorId = null): bool
    {
        return $this->variants()
            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
            ->where('stock', '>', 0)
            ->exists();
    }
}
