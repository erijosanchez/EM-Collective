<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'alt', 'sort_order', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    // Máximo 5 imágenes por producto — validar en el Request/Service
    public const MAX_IMAGES = 5;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
