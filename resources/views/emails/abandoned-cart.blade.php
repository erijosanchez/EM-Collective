@extends('emails.layout')
@section('subject', '¿Olvidaste algo, ' . explode(' ', $name)[0] . '? Tu carrito te espera 🛍️')

@section('hero')
<p class="hero-eyebrow">👀 Tu carrito sigue aquí</p>
<h1 class="hero-title">Dejaste algo<br><em style="font-style:italic;color:#E84B3A">muy especial</em></h1>
<p class="hero-subtitle">Notamos que dejaste productos increíbles en tu carrito. ¡No dejes que otro se los lleve!</p>
@endsection

@section('body')

{{-- Urgencia --}}
<div style="background:linear-gradient(135deg,#1A1A18,#2d2d2a);border-left:3px solid #E84B3A;padding:18px 24px;margin-bottom:28px;text-align:center">
    <p style="color:#E84B3A;font-size:10px;letter-spacing:0.15em;text-transform:uppercase;margin-bottom:4px">⏰ Stock limitado</p>
    <p style="color:#F5F0E8;font-size:14px;font-weight:300">Estos productos tienen alta demanda. No podemos garantizar disponibilidad por mucho tiempo.</p>
</div>

{{-- Productos del carrito --}}
<p class="section-title" style="margin-bottom:20px">Lo que dejaste en tu carrito</p>

@foreach($items as $item)
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;border:1px solid #F0EBE3">
    <tr>
        {{-- Imagen --}}
        <td width="80" style="padding:0;vertical-align:top">
            @if($item['image'])
            <img src="{{ $item['image'] }}" width="80" height="96" style="width:80px;height:96px;object-fit:cover;display:block" alt="{{ $item['name'] }}">
            @else
            <div style="width:80px;height:96px;background:#F5F0E8;display:flex;align-items:center;justify-content:center">
                <span style="font-size:24px">👗</span>
            </div>
            @endif
        </td>
        {{-- Info --}}
        <td style="padding:14px 16px;vertical-align:top">
            <p style="font-size:13px;font-weight:600;color:#1A1A18;margin-bottom:4px">{{ $item['name'] }}</p>
            @if($item['variant'])
            <p style="font-size:11px;color:#8A8880;margin-bottom:8px">{{ $item['variant'] }}</p>
            @endif
            <p style="font-size:11px;color:#8A8880">Cantidad: {{ $item['quantity'] }}</p>
        </td>
        {{-- Precio --}}
        <td style="padding:14px 16px;vertical-align:middle;text-align:right;white-space:nowrap">
            @if($item['sale_price'])
            <p style="font-size:15px;font-weight:700;color:#1A1A18">S/ {{ number_format($item['sale_price'] * $item['quantity'], 2) }}</p>
            <p style="font-size:11px;color:#E84B3A;text-decoration:line-through">S/ {{ number_format($item['base_price'] * $item['quantity'], 2) }}</p>
            @else
            <p style="font-size:15px;font-weight:700;color:#1A1A18">S/ {{ number_format($item['base_price'] * $item['quantity'], 2) }}</p>
            @endif
        </td>
    </tr>
</table>
@endforeach

{{-- Total del carrito --}}
<div style="background:#F5F0E8;padding:16px 20px;margin:8px 0 28px;display:flex;justify-content:space-between">
    <table width="100%">
    <tr>
        <td style="font-size:13px;color:#8A8880">Total de tu carrito</td>
        <td style="text-align:right;font-size:20px;font-weight:700;color:#1A1A18;font-family:Georgia,serif">S/ {{ number_format($cartTotal, 2) }}</td>
    </tr>
    </table>
</div>

{{-- CTA principal --}}
<div class="cta-wrap" style="padding:8px 0 24px">
    <a href="{{ $cartUrl }}" class="btn-main btn-terracota" style="font-size:13px;padding:18px 56px;letter-spacing:0.12em">
        🛍️ Volver a mi carrito
    </a>
    <p style="margin-top:14px;font-size:11px;color:#8A8880">Un clic y retomas donde lo dejaste</p>
</div>

<div class="divider"></div>

{{-- Beneficios de comprar ahora --}}
<p class="section-title">¿Por qué comprar en EM Collective?</p>
<table width="100%" cellpadding="0" cellspacing="0">
    @foreach([
        ['✅', 'Calidad garantizada', 'Cada prenda pasa por control de calidad antes de llegar a ti'],
        ['🚚', 'Envío a todo el Perú', 'Recibe tu pedido en 1-7 días hábiles'],
        ['🔄', 'Cambios sin costo', 'Si no es tu talla, te lo cambiamos gratis en 30 días'],
    ] as $benefit)
    <tr>
        <td style="padding:10px 0;vertical-align:top;width:36px;font-size:20px">{{ $benefit[0] }}</td>
        <td style="padding:10px 0 10px 12px;border-bottom:1px solid #F0EBE3">
            <strong style="font-size:13px;color:#1A1A18;display:block;margin-bottom:3px">{{ $benefit[1] }}</strong>
            <span style="font-size:12px;color:#8A8880">{{ $benefit[2] }}</span>
        </td>
    </tr>
    @endforeach
</table>

<p style="text-align:center;font-size:12px;color:#8A8880;margin-top:24px">
    ¿Tienes dudas? Escríbenos por
    @if(\App\Models\Setting::get('store_whatsapp'))
    <a href="https://wa.me/{{ preg_replace('/\D/','',$wa=\App\Models\Setting::get('store_whatsapp')) }}" style="color:#E84B3A">WhatsApp</a>
    @endif
    — respondemos en minutos.
</p>

@endsection
