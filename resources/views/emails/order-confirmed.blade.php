@extends('emails.layout')

@section('body')
<h2>¡Tu pedido fue confirmado!</h2>
<p>Hola <strong>{{ $order->customer_name }}</strong>, gracias por tu compra en EM Collective.</p>

<div class="highlight">
    <strong style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #8A8880;">Número de pedido</strong><br>
    <span style="font-size: 20px; font-family: Georgia, serif; font-weight: 300;">{{ $order->order_number }}</span>
</div>

<div class="divider"></div>
<h2 style="font-size: 18px;">Resumen del pedido</h2>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th style="text-align: right;">Cant.</th>
            <th style="text-align: right;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->product_name }}
                @if($item->variant_label)
                <br><span style="font-size: 12px; color: #8A8880;">{{ $item->variant_label }}</span>
                @endif
            </td>
            <td style="text-align: right;">× {{ $item->quantity }}</td>
            <td style="text-align: right;">S/ {{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @if($order->discount_amount > 0)
        <tr><td colspan="2" style="text-align: right; color: #C4714A;">Descuento</td><td style="text-align: right; color: #C4714A;">-S/ {{ number_format($order->discount_amount, 2) }}</td></tr>
        @endif
        <tr><td colspan="2" style="text-align: right; color: #8A8880;">Envío</td><td style="text-align: right;">{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : 'Gratis' }}</td></tr>
        <tr style="font-weight: 600;"><td colspan="2" style="text-align: right; font-size: 15px;">Total</td><td style="text-align: right; font-size: 15px;">S/ {{ number_format($order->total, 2) }}</td></tr>
    </tfoot>
</table>

<div class="divider"></div>
<table style="background: #f4efe6;">
    <tr>
        <td style="width: 50%; border: none; padding: 16px 20px; vertical-align: top;">
            <strong style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px; color: #8A8880;">Envío a</strong>
            <span style="font-size: 13px;">{{ $order->shipping_address }}<br>{{ $order->shipping_district }}, {{ $order->shipping_province }}<br>{{ $order->shipping_department }}</span>
        </td>
        <td style="width: 50%; border: none; padding: 16px 20px; vertical-align: top;">
            <strong style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px; color: #8A8880;">Método de pago</strong>
            <span style="font-size: 13px;">{{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</span>
        </td>
    </tr>
</table>

<div style="text-align: center; margin-top: 32px;">
    <a href="{{ url('/mi-cuenta/pedidos') }}" class="btn">Ver mis pedidos</a>
</div>

<p style="margin-top: 24px; font-size: 12px; color: #8A8880; text-align: center;">
    ¿Preguntas? Escríbenos a <a href="mailto:{{ \App\Models\Setting::get('store_email', 'contacto@emcollective.pe') }}" style="color: #C4714A;">{{ \App\Models\Setting::get('store_email') }}</a>
    o por WhatsApp al {{ \App\Models\Setting::get('store_phone') }}.
</p>
@endsection
