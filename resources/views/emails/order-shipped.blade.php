@extends('emails.layout')

@section('body')
<h2>¡Tu pedido está en camino! 🚚</h2>
<p>Hola <strong>{{ $order->customer_name }}</strong>, tu pedido <strong>{{ $order->order_number }}</strong> ya fue enviado y pronto llegará a ti.</p>

@if($order->tracking_code)
<div class="highlight">
    <strong style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #8A8880;">Código de tracking</strong><br>
    <span style="font-size: 20px; font-family: monospace; letter-spacing: 0.1em;">{{ $order->tracking_code }}</span>
</div>
@endif

{{-- Timeline --}}
<div style="margin: 28px 0;">
    @php
    $steps = [
        ['label' => 'Pedido recibido', 'done' => true],
        ['label' => 'En preparación', 'done' => true],
        ['label' => 'Enviado', 'done' => true],
        ['label' => 'Entregado', 'done' => false],
    ];
    @endphp
    <table cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
            @foreach($steps as $i => $step)
            <td style="text-align: center; padding: 0 4px; width: 25%;">
                <div style="width: 32px; height: 32px; border-radius: 50%; margin: 0 auto 8px;
                     background: {{ $step['done'] ? '#C4714A' : '#e8e3da' }};
                     display: flex; align-items: center; justify-content: center;">
                    @if($step['done'])
                    <span style="color: white; font-size: 16px;">✓</span>
                    @else
                    <span style="color: #8A8880; font-size: 12px;">{{ $i + 1 }}</span>
                    @endif
                </div>
                <p style="font-size: 11px; text-align: center; color: {{ $step['done'] ? '#1A1A18' : '#8A8880' }}; margin: 0;">{{ $step['label'] }}</p>
            </td>
            @endforeach
        </tr>
    </table>
</div>

<div class="divider"></div>

<table style="background: #f4efe6;">
    <tr>
        <td style="border: none; padding: 16px 20px; vertical-align: top;">
            <strong style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px; color: #8A8880;">Dirección de entrega</strong>
            <span style="font-size: 13px;">{{ $order->shipping_address }}<br>{{ $order->shipping_district }}, {{ $order->shipping_province }}</span>
        </td>
    </tr>
</table>

<div style="text-align: center; margin-top: 28px;">
    @if($wa = \App\Models\Setting::get('store_whatsapp'))
    <a href="https://wa.me/{{ $wa }}?text=Hola!+Consulto+por+mi+pedido+{{ $order->order_number }}" class="btn">
        Consultar por WhatsApp
    </a>
    @endif
</div>

<p style="margin-top: 24px; font-size: 12px; color: #8A8880; text-align: center;">
    Tiempo estimado de entrega: {{ \App\Models\Setting::get('shipping_days_lima', '1-2') }} días hábiles en Lima, {{ \App\Models\Setting::get('shipping_days_provinces', '3-7') }} días en provincias.
</p>
@endsection
