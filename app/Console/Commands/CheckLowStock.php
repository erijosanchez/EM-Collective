<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlertMail;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLowStock extends Command
{
    protected $signature   = 'shop:check-low-stock';
    protected $description = 'Envía alerta de stock bajo al administrador';

    public function handle(): void
    {
        $lowStock = Product::active()
            ->with(['category', 'variants'])
            ->get()
            ->filter(fn($p) => $p->total_stock <= $p->low_stock_threshold);

        if ($lowStock->isEmpty()) {
            $this->info('No hay productos con stock bajo.');
            return;
        }

        $adminEmail = Setting::get('admin_email') ?? config('mail.from.address');

        if (!$adminEmail) {
            $this->error('No hay email de admin configurado.');
            return;
        }

        Mail::to($adminEmail)->send(new LowStockAlertMail($lowStock));

        $this->info("Alerta enviada a {$adminEmail} — {$lowStock->count()} producto(s) con stock bajo.");
    }
}
