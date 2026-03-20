<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    protected $fillable = ['name', 'code', 'type', 'sort_order'];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeAdult($query)
    {
        return $query->where('type', 'adult');
    }

    public function scopeKids($query)
    {
        return $query->where('type', 'kids');
    }

    public function scopeNumeric($query)
    {
        return $query->where('type', 'numeric');
    }
}
