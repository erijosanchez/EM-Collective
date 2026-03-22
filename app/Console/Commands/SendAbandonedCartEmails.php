<?php

namespace App\Console\Commands;

use App\Mail\AbandonedCartMail;
use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartEmails extends Command
{
    protected $signature   = 'shop:send-abandoned-carts';
    protected $description = 'Envía emails de recuperación para carritos abandonados (1h sin actividad)';

    public function handle(): void
    {
        // Carritos con email, activos hace más de 1 hora y menos de 24h,
        // sin compra completada, sin email previo enviado, con al menos 1 ítem
        $carts = Cart::with(['items.product.images', 'items.variant', 'user'])
            ->whereNotNull('user_email')
            ->where('abandoned_email_sent', false)
            ->where('last_active_at', '<=', now()->subHour())
            ->where('last_active_at', '>=', now()->subDay())
            ->whereHas('items')
            ->get();

        $sent = 0;

        foreach ($carts as $cart) {
            try {
                Mail::to($cart->user_email)->send(new AbandonedCartMail($cart));

                $cart->update(['abandoned_email_sent' => true]);
                $sent++;

                $this->line("✓ Enviado a {$cart->user_email}");
            } catch (\Exception $e) {
                $this->error("Error con {$cart->user_email}: {$e->getMessage()}");
            }
        }

        $this->info("Proceso completado: {$sent} email(s) enviado(s).");
    }
}
