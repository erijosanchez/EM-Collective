<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'first_name',
        'last_name',
        'phone',
        'dni',
        'department',
        'province',
        'district',
        'address',
        'reference',
        'zip_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->district}, {$this->province}, {$this->department}";
    }

    /**
     * Al crear una dirección, si es la primera del usuario, hacerla default.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Address $address) {
            if ($address->user_id && !static::where('user_id', $address->user_id)->exists()) {
                $address->is_default = true;
            }
        });

        // Al marcar como default, quitar default a las demás
        static::saved(function (Address $address) {
            if ($address->is_default) {
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
