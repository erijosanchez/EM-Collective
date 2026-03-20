<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = [
            'general'  => Setting::group('general'),
            'seo'      => Setting::group('seo'),
            'shipping' => Setting::group('shipping'),
            'payments' => Setting::group('payments'),
            'social'   => Setting::group('social'),
            'email'    => Setting::group('email'),
            'store'    => Setting::group('store'),
        ];

        return view('admin.settings', compact('groups'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            // General
            'store_name'         => 'nullable|string|max:100',
            'store_tagline'      => 'nullable|string|max:200',
            'store_email'        => 'nullable|email|max:150',
            'store_phone'        => 'nullable|string|max:30',
            'store_whatsapp'     => 'nullable|string|max:30',
            'store_address'      => 'nullable|string|max:300',
            // SEO
            'seo_home_title'      => 'nullable|string|max:160',
            'seo_home_description' => 'nullable|string|max:320',
            'seo_home_keywords'   => 'nullable|string|max:300',
            'google_analytics_id' => 'nullable|string|max:30',
            'facebook_pixel_id'   => 'nullable|string|max:30',
            // Shipping
            'shipping_free_threshold' => 'nullable|numeric|min:0',
            'shipping_default_cost'   => 'nullable|numeric|min:0',
            'shipping_lima_cost'      => 'nullable|numeric|min:0',
            'shipping_provinces_cost' => 'nullable|numeric|min:0',
            'shipping_days_lima'      => 'nullable|string|max:20',
            'shipping_days_provinces' => 'nullable|string|max:20',
            // Payments
            'contra_entrega_enabled' => 'nullable|in:0,1',
            // Social
            'social_facebook'  => 'nullable|url|max:300',
            'social_instagram'  => 'nullable|url|max:300',
            'social_tiktok'     => 'nullable|url|max:300',
            'social_youtube'    => 'nullable|url|max:300',
            // Email
            'mail_from_name'    => 'nullable|string|max:100',
            'mail_from_address' => 'nullable|email|max:150',
            'mail_footer_text'  => 'nullable|string|max:300',
            // Store
            'products_per_page'       => 'nullable|integer|min:4|max:100',
            'reviews_enabled'         => 'nullable|in:0,1',
            'wishlist_enabled'        => 'nullable|in:0,1',
            'announcement_bar_text'   => 'nullable|string|max:300',
            'announcement_bar_active' => 'nullable|in:0,1',
        ]);

        Setting::setMany($data);

        return redirect()->route('admin.settings')
            ->with('success', 'Configuración guardada correctamente.');
    }
}
