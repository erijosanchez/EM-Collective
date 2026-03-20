@extends('admin.layouts.admin')
@section('title', 'Ajustes')

@section('content')
<h1 class="font-serif text-3xl font-light mb-6">Ajustes de la tienda</h1>

<form action="{{ route('admin.settings.update') }}" method="POST" x-data="{ tab: 'general' }">
    @csrf @method('PUT')

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-stone/10 overflow-x-auto">
        @foreach([
            'general'  => 'General',
            'seo'      => 'SEO',
            'shipping' => 'Envíos',
            'payments' => 'Pagos',
            'social'   => 'Social',
            'email'    => 'Email',
            'store'    => 'Tienda',
        ] as $key => $label)
        <button type="button" @click="tab = '{{ $key }}'"
                class="pb-3 px-3 text-xs uppercase tracking-widest whitespace-nowrap border-b-2 transition-colors -mb-px"
                :class="tab === '{{ $key }}' ? 'border-terracota text-terracota' : 'border-transparent text-stone hover:text-cream'">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- General --}}
    <div x-show="tab === 'general'" class="card p-6 space-y-4 max-w-xl">
        @foreach([
            ['store_name', 'Nombre de la tienda'],
            ['store_tagline', 'Tagline'],
            ['store_email', 'Email de contacto'],
            ['store_phone', 'Teléfono'],
            ['store_whatsapp', 'WhatsApp (solo número, ej: 51987654321)'],
            ['store_address', 'Dirección física'],
        ] as [$key, $label])
        <div>
            <label class="form-label">{{ $label }}</label>
            <input type="text" name="{{ $key }}" value="{{ old($key, $groups['general'][$key] ?? '') }}" class="form-input">
        </div>
        @endforeach
    </div>

    {{-- SEO --}}
    <div x-show="tab === 'seo'" class="card p-6 space-y-4 max-w-xl">
        <div>
            <label class="form-label">Título SEO inicio</label>
            <input type="text" name="seo_home_title" value="{{ old('seo_home_title', $groups['seo']['seo_home_title'] ?? '') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Descripción SEO inicio</label>
            <textarea name="seo_home_description" rows="3" class="form-input">{{ old('seo_home_description', $groups['seo']['seo_home_description'] ?? '') }}</textarea>
        </div>
        <div>
            <label class="form-label">Keywords</label>
            <input type="text" name="seo_home_keywords" value="{{ old('seo_home_keywords', $groups['seo']['seo_home_keywords'] ?? '') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Google Analytics ID</label>
            <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $groups['seo']['google_analytics_id'] ?? '') }}" class="form-input" placeholder="G-XXXXXXXXXX">
        </div>
        <div>
            <label class="form-label">Facebook Pixel ID</label>
            <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $groups['seo']['facebook_pixel_id'] ?? '') }}" class="form-input">
        </div>
    </div>

    {{-- Envíos --}}
    <div x-show="tab === 'shipping'" class="card p-6 space-y-4 max-w-xl">
        @foreach([
            ['shipping_free_threshold', 'Monto mínimo envío gratis (S/)'],
            ['shipping_default_cost', 'Costo de envío por defecto (S/)'],
            ['shipping_lima_cost', 'Costo envío Lima (S/)'],
            ['shipping_provinces_cost', 'Costo envío provincias (S/)'],
            ['shipping_days_lima', 'Días entrega Lima (ej: 1-2)'],
            ['shipping_days_provinces', 'Días entrega provincias (ej: 3-7)'],
        ] as [$key, $label])
        <div>
            <label class="form-label">{{ $label }}</label>
            <input type="text" name="{{ $key }}" value="{{ old($key, $groups['shipping'][$key] ?? '') }}" class="form-input !w-48">
        </div>
        @endforeach
    </div>

    {{-- Pagos --}}
    <div x-show="tab === 'payments'" class="card p-6 space-y-4 max-w-xl">
        <div class="bg-stone/10 p-4 text-xs text-stone rounded">
            Las claves de MercadoPago se configuran en el archivo <code>.env</code> del servidor por seguridad.
        </div>
        <label class="flex items-center justify-between">
            <span class="text-sm text-stone">Habilitar contra entrega</span>
            <select name="contra_entrega_enabled" class="form-input !w-24">
                <option value="1" {{ ($groups['payments']['contra_entrega_enabled'] ?? '1') === '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ ($groups['payments']['contra_entrega_enabled'] ?? '1') === '0' ? 'selected' : '' }}>No</option>
            </select>
        </label>
    </div>

    {{-- Social --}}
    <div x-show="tab === 'social'" class="card p-6 space-y-4 max-w-xl">
        @foreach(['social_facebook' => 'Facebook', 'social_instagram' => 'Instagram', 'social_tiktok' => 'TikTok', 'social_youtube' => 'YouTube'] as $key => $label)
        <div>
            <label class="form-label">{{ $label }} URL</label>
            <input type="url" name="{{ $key }}" value="{{ old($key, $groups['social'][$key] ?? '') }}" class="form-input" placeholder="https://...">
        </div>
        @endforeach
    </div>

    {{-- Email --}}
    <div x-show="tab === 'email'" class="card p-6 space-y-4 max-w-xl">
        <div><label class="form-label">Nombre del remitente</label><input type="text" name="mail_from_name" value="{{ old('mail_from_name', $groups['email']['mail_from_name'] ?? '') }}" class="form-input"></div>
        <div><label class="form-label">Email remitente</label><input type="email" name="mail_from_address" value="{{ old('mail_from_address', $groups['email']['mail_from_address'] ?? '') }}" class="form-input"></div>
        <div><label class="form-label">Texto del footer del email</label><input type="text" name="mail_footer_text" value="{{ old('mail_footer_text', $groups['email']['mail_footer_text'] ?? '') }}" class="form-input"></div>
    </div>

    {{-- Tienda --}}
    <div x-show="tab === 'store'" class="card p-6 space-y-4 max-w-xl">
        <div><label class="form-label">Productos por página</label><input type="number" name="products_per_page" min="4" max="100" value="{{ old('products_per_page', $groups['store']['products_per_page'] ?? 24) }}" class="form-input !w-24"></div>
        <div>
            <label class="form-label">Texto barra de anuncio</label>
            <input type="text" name="announcement_bar_text" value="{{ old('announcement_bar_text', $groups['store']['announcement_bar_text'] ?? '') }}" class="form-input">
        </div>
        @foreach([
            ['announcement_bar_active', 'Barra de anuncio activa'],
            ['reviews_enabled', 'Reseñas habilitadas'],
            ['wishlist_enabled', 'Wishlist habilitada'],
        ] as [$key, $label])
        <label class="flex items-center justify-between">
            <span class="text-sm text-stone">{{ $label }}</span>
            <select name="{{ $key }}" class="form-input !w-24">
                <option value="1" {{ ($groups['store'][$key] ?? '1') === '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ ($groups['store'][$key] ?? '1') === '0' ? 'selected' : '' }}>No</option>
            </select>
        </label>
        @endforeach
    </div>

    <div class="mt-6">
        <button type="submit" class="btn-admin btn-admin-primary">Guardar configuración</button>
    </div>
</form>
@endsection
