@extends('emails.layout')
@section('subject', '¡Tu pedido #' . $order->order_number . ' está confirmado! 🎉')

@section('hero')
<p class="hero-eyebrow">✓ Pedido confirmado</p>
<h1 class="hero-title">¡Gracias por<br>tu compra, {{ explode(' ', $order->customer_name)[0] }}!</h1>
<p class="hero-subtitle">Estamos preparando tu pedido con mucho cuidado. Te avisaremos cuando esté en camino.</p>
@endsection

@section('body')

{{-- Número de pedido --}}
<div class="highlight-box">
    <span class="order-number-label">Número de pedido</span>
    <div class="order-number-value">#{{ $order->order_number }}</div>
    <div style="margin-top:10px;font-size:12px;color:#8A8880">
        Fecha: {{ $order->created_at->format('d/m/Y H:i') }}
    </div>
</div>

{{-- Tracker de estado --}}
<div class="tracker" style="margin:28px 0">
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="25%" style="text-align:center;padding:0 4px">
            <div style="width:32px;height:32px;background:#C4714A;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-size:14px;line-height:32px;text-align:center">✓</div>
            <div style="font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:#C4714A;font-weight:600">Recibido</div>
        </td>
        <td style="padding:0;vertical-align:middle;padding-bottom:24px">
            <div style="height:2px;background:#C4714A"></div>
        </td>
        <td width="25%" style="text-align:center;padding:0 4px">
            <div style="width:32px;height:32px;background:#1A1A18;border-radius:50%;margin:0 auto 8px;font-size:14px;line-height:32px;text-align:center;color:#F5F0E8">⚡</div>
            <div style="font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:#1A1A18;font-weight:600">Preparando</div>
        </td>
        <td style="padding:0;vertical-align:middle;padding-bottom:24px">
            <div style="height:2px;background:#EDE8DF"></div>
        </td>
        <td width="25%" style="text-align:center;padding:0 4px">
            <div style="width:32px;height:32px;background:#D5CFC6;border-radius:50%;margin:0 auto 8px;font-size:14px;line-height:32px;text-align:center;color:#8A8880">🚚</div>
            <div style="font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:#8A8880">En camino</div>
        </td>
        <td style="padding:0;vertical-align:middle;padding-bottom:24px">
            <div style="height:2px;background:#EDE8DF"></div>
        </td>
        <td width="25%" style="text-align:center;padding:0 4px">
            <div style="width:32px;height:32px;background:#D5CFC6;border-radius:50%;margin:0 auto 8px;font-size:14px;line-height:32px;text-align:center;color:#8A8880">📦</div>
            <div style="font-size:9px;text-transform:uppercase;letter-spacing:0.1em;color:#8A8880">Entregado</div>
        </td>
    </tr>
    </table>
</div>

<div class="divider"></div>

{{-- Productos --}}
<p class="section-title">Resumen del pedido</p>

<table class="product-table" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th style="text-align:left;padding:10px 14px">Producto</th>
            <th style="text-align:center;width:50px">Cant.</th>
            <th style="text-align:right;width:90px">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="padding:14px">
                <strong style="font-size:13px;color:#1A1A18">{{ $item->product_name }}</strong>
                @if($item->variant_label)
                <br><span style="font-size:11px;color:#8A8880;margin-top:2px;display:inline-block">{{ $item->variant_label }}</span>
                @endif
            </td>
            <td style="text-align:center;padding:14px;color:#8A8880">× {{ $item->quantity }}</td>
            <td style="text-align:right;padding:14px;font-weight:500">S/ {{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align:right;padding:10px 14px;color:#8A8880;border-bottom:1px solid #F0EBE3">Subtotal</td>
            <td style="text-align:right;padding:10px 14px;border-bottom:1px solid #F0EBE3">S/ {{ number_format($order->subtotal ?? ($order->total - $order->shipping_cost + $order->discount_amount), 2) }}</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td colspan="2" style="text-align:right;padding:8px 14px;color:#C4714A;border-bottom:1px solid #F0EBE3">Descuento</td>
            <td style="text-align:right;padding:8px 14px;color:#C4714A;border-bottom:1px solid #F0EBE3">-S/ {{ number_format($order->discount_amount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="text-align:right;padding:8px 14px;color:#8A8880;border-bottom:1px solid #F0EBE3">Envío</td>
            <td style="text-align:right;padding:8px 14px;border-bottom:1px solid #F0EBE3">{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : '🎁 Gratis' }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="2" style="text-align:right;padding:16px 14px;font-size:15px;font-weight:700;border-top:2px solid #1A1A18">TOTAL</td>
            <td style="text-align:right;padding:16px 14px;font-size:18px;font-weight:700;color:#1A1A18;border-top:2px solid #1A1A18">S/ {{ number_format($order->total, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="divider"></div>

{{-- Dirección y pago --}}
<table width="100%" class="info-grid" cellpadding="0" cellspacing="0">
    <tr>
        <td class="info-cell" width="50%">
            <span class="info-label">📍 Dirección de envío</span>
            <span class="info-value">
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_district }}, {{ $order->shipping_province }}<br>
                {{ $order->shipping_department }}
            </span>
        </td>
        <td class="info-cell" width="50%" style="border-left:1px solid #E8E3DA">
            <span class="info-label">💳 Método de pago</span>
            <span class="info-value">{{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</span>
            <br><br>
            <span class="info-label">📞 Contacto</span>
            <span class="info-value">{{ $order->customer_phone ?? '-' }}</span>
        </td>
    </tr>
</table>

{{-- CTA --}}
<div class="cta-wrap">
    <a href="{{ url('/mi-cuenta/pedidos') }}" class="btn-main btn-shimmer">
        Ver estado de mi pedido →
    </a>
</div>

<p style="text-align:center;font-size:12px;color:#8A8880;margin-top:20px">
    ¿Tienes alguna pregunta? Escríbenos por
    @if(\App\Models\Setting::get('store_whatsapp'))
    <a href="https://wa.me/{{ preg_replace('/\D/','',$wa=\App\Models\Setting::get('store_whatsapp')) }}" style="color:#C4714A">WhatsApp</a> o a
    @endif
    <a href="mailto:{{ \App\Models\Setting::get('store_email','contacto@emcollective.pe') }}" style="color:#C4714A">{{ \App\Models\Setting::get('store_email','contacto@emcollective.pe') }}</a>
</p>

@endsection
