<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Tareas programadas ──────────────────────────────────────────
// Verificar stock bajo cada día a las 8am
Schedule::command('shop:check-low-stock')->dailyAt('08:00');

// Carritos abandonados: revisar cada hora
Schedule::command('shop:send-abandoned-carts')->hourly();
