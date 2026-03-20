<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'coupon_id',
        'guest_email',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_dni',
        'shipping_department',
        'shipping_province',
        'shipping_district',
        'shipping_address',
        'shipping_reference',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'status',
        'notes',
        'admin_notes',
        'tracking_code',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost'   => 'decimal:2',
        'total'           => 'decimal:2',
        'shipped_at'      => 'datetime',
        'delivered_at'    => 'datetime',
    ];

    // Etiquetas de estado para vistas
    public const STATUS_LABELS = [
        'pending'    => 'Pendiente',
        'confirmed'  => 'Confirmado',
        'processing' => 'Preparando',
        'shipped'    => 'Enviado',
        'delivered'  => 'Entregado',
        'cancelled'  => 'Cancelado',
        'refunded'   => 'Reembolsado',
    ];

    public const STATUS_COLORS = [
        'pending'    => 'yellow',
        'confirmed'  => 'blue',
        'processing' => 'indigo',
        'shipped'    => 'purple',
        'delivered'  => 'green',
        'cancelled'  => 'red',
        'refunded'   => 'gray',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Y') . '-' . str_pad(
                    (Order::whereYear('created_at', date('Y'))->count() + 1),
                    5,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

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
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getIsGuestAttribute(): bool
    {
        return is_null($this->user_id);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
