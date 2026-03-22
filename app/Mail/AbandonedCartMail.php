<?php

namespace App\Mail;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $cartUrl;
    public float  $cartTotal;
    public array  $items;

    public function __construct(Cart $cart)
    {
        $this->name     = $cart->user?->name ?? 'Cliente';
        $this->cartUrl  = url('/carrito');
        $this->cartTotal = $cart->total;

        $this->items = $cart->items->map(function ($item) {
            $product = $item->product;
            return [
                'name'       => $product->name,
                'variant'    => $item->variant?->label,
                'quantity'   => $item->quantity,
                'base_price' => $product->base_price,
                'sale_price' => $product->sale_price,
                'image'      => $product->images->first()
                    ? asset('storage/' . $product->images->first()->path)
                    : null,
            ];
        })->toArray();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¿Olvidaste algo, ' . explode(' ', $this->name)[0] . '? Tu carrito te espera 🛍️',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.abandoned-cart');
    }

    public function attachments(): array { return []; }
}
