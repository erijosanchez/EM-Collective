<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // ── General ──────────────────────────────────────────────────
            ['key' => 'store_name',         'value' => 'EM Collective',                      'group' => 'general'],
            ['key' => 'store_tagline',       'value' => 'Moda editorial para toda la familia', 'group' => 'general'],
            ['key' => 'store_email',         'value' => 'contacto@emcollective.pe',            'group' => 'general'],
            ['key' => 'store_phone',         'value' => '+51 987 654 321',                    'group' => 'general'],
            ['key' => 'store_whatsapp',      'value' => '51987654321',                        'group' => 'general'],
            ['key' => 'store_address',       'value' => 'Lima, Perú',                         'group' => 'general'],
            ['key' => 'store_logo',          'value' => null,                                 'group' => 'general'],
            ['key' => 'store_favicon',       'value' => null,                                 'group' => 'general'],
            ['key' => 'currency',            'value' => 'PEN',                                'group' => 'general'],
            ['key' => 'currency_symbol',     'value' => 'S/',                                 'group' => 'general'],

            // ── SEO ───────────────────────────────────────────────────────
            ['key' => 'seo_home_title',      'value' => 'EM Collective | Moda editorial para toda la familia', 'group' => 'seo'],
            ['key' => 'seo_home_description', 'value' => 'Descubre moda para hombre, mujer y niños al mejor precio. Envío a todo el Perú. Paga con Mercado Pago o contra entrega.', 'group' => 'seo'],
            ['key' => 'seo_home_keywords',   'value' => 'ropa peru, tienda online, moda, em collective, ropa hombre, ropa mujer, ropa niños', 'group' => 'seo'],
            ['key' => 'google_analytics_id', 'value' => null,                                 'group' => 'seo'],
            ['key' => 'facebook_pixel_id',   'value' => null,                                 'group' => 'seo'],

            // ── Envíos ───────────────────────────────────────────────────
            ['key' => 'shipping_free_threshold', 'value' => '150',     'group' => 'shipping'],  // S/ mínimo para envío gratis
            ['key' => 'shipping_default_cost',   'value' => '12',      'group' => 'shipping'],  // S/ costo base
            ['key' => 'shipping_lima_cost',      'value' => '8',       'group' => 'shipping'],
            ['key' => 'shipping_provinces_cost', 'value' => '15',      'group' => 'shipping'],
            ['key' => 'shipping_days_lima',      'value' => '1-2',     'group' => 'shipping'],
            ['key' => 'shipping_days_provinces', 'value' => '3-7',     'group' => 'shipping'],

            // ── Pagos ────────────────────────────────────────────────────
            ['key' => 'mercadopago_public_key',  'value' => null, 'group' => 'payments'],
            ['key' => 'mercadopago_access_token', 'value' => null, 'group' => 'payments'],
            ['key' => 'mercadopago_sandbox',     'value' => '1', 'group' => 'payments'],  // 1=sandbox, 0=producción
            ['key' => 'contra_entrega_enabled',  'value' => '1', 'group' => 'payments'],

            // ── Social ───────────────────────────────────────────────────
            ['key' => 'social_facebook',    'value' => null, 'group' => 'social'],
            ['key' => 'social_instagram',   'value' => null, 'group' => 'social'],
            ['key' => 'social_tiktok',      'value' => null, 'group' => 'social'],
            ['key' => 'social_youtube',     'value' => null, 'group' => 'social'],

            // ── Emails ───────────────────────────────────────────────────
            ['key' => 'mail_from_name',     'value' => 'EM Collective',                'group' => 'email'],
            ['key' => 'mail_from_address',  'value' => 'no-reply@emcollective.pe',   'group' => 'email'],
            ['key' => 'mail_footer_text',   'value' => '© 2026 EM Collective. Todos los derechos reservados.', 'group' => 'email'],

            // ── Tienda ───────────────────────────────────────────────────
            ['key' => 'products_per_page',       'value' => '24',   'group' => 'store'],
            ['key' => 'reviews_enabled',         'value' => '1',    'group' => 'store'],
            ['key' => 'wishlist_enabled',        'value' => '1',    'group' => 'store'],
            ['key' => 'maintenance_mode',        'value' => '0',    'group' => 'store'],
            ['key' => 'maintenance_message',     'value' => 'Estamos en mantenimiento. Volvemos pronto.', 'group' => 'store'],
            ['key' => 'announcement_bar_text',   'value' => '🚚 Envío gratis en compras mayores a S/ 150', 'group' => 'store'],
            ['key' => 'announcement_bar_active', 'value' => '1',    'group' => 'store'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Configuraciones creadas: ' . count($settings));
    }
}
