<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Campaign extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'body_html',
        'body_text',
        'segment',
        'status',
        'sent_count',
        'open_count',
        'click_count',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function sends(): HasMany
    {
        return $this->hasMany(CampaignSend::class);
    }

    public function getOpenRateAttribute(): float
    {
        if (!$this->sent_count) return 0;
        return round(($this->open_count / $this->sent_count) * 100, 1);
    }
}
