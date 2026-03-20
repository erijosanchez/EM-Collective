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

    // ─── Relationships ────────────────────────────────────────────────────

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sends(): HasMany
    {
        return $this->hasMany(CampaignSend::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────

    public function getOpenRateAttribute(): float
    {
        if (!$this->sent_count) return 0;
        return round(($this->open_count / $this->sent_count) * 100, 1);
    }

    public function getClickRateAttribute(): float
    {
        if (!$this->sent_count) return 0;
        return round(($this->click_count / $this->sent_count) * 100, 1);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Borrador',
            'scheduled' => 'Programada',
            'sending'   => 'Enviando...',
            'sent'      => 'Enviada',
            'failed'    => 'Error',
            default     => $this->status,
        };
    }

    public function getIsEditableAttribute(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }
}
