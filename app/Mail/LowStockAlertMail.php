<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Collection $products) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Alerta de stock bajo — ' . $this->products->count() . ' producto(s) requieren atención',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.low-stock-alert');
    }

    public function attachments(): array { return []; }
}
