@extends('emails.layout')
@php $unsubscribeUrl = route('newsletter.unsubscribe', $subscriber->unsubscribe_token); @endphp

@section('body')
<h2>¡Bienvenida a EM Collective! 🎉</h2>
<p>
    @if($subscriber->name) Hola <strong>{{ $subscriber->name }}</strong>, ¡@else Hola, ¡@endif
    gracias por unirte a nuestra comunidad. Ya formas parte de las primeras en enterarse de nuevas colecciones, ofertas exclusivas y tendencias editoriales.
</p>

{{-- Cupón de bienvenida --}}
<div style="background: #1A1A18; padding: 32px; text-align: center; margin: 24px 0;">
    <p style="color: #8A8880; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">Tu código de bienvenida</p>
    <p style="font-size: 36px; font-family: Georgia, serif; font-weight: 300; color: #F5F0E8; letter-spacing: 0.2em; margin: 0;">BIENVENIDO10</p>
    <p style="color: #C4714A; font-size: 12px; margin-top: 8px;">10% de descuento en tu primera compra</p>
</div>

<p style="font-size: 12px; color: #8A8880; text-align: center;">Ingresa el código al hacer checkout. Válido por 30 días.</p>

<div class="divider"></div>

<h2 style="font-size: 20px;">Qué obtienes como suscriptora</h2>
<table cellpadding="0" cellspacing="0" style="margin: 16px 0;">
    @foreach([
        ['✦', 'Acceso anticipado a nuevas colecciones'],
        ['✦', 'Ofertas exclusivas para suscriptoras'],
        ['✦', 'Guías de tendencias y estilo editorial'],
        ['✦', 'Invitaciones a eventos y preventa'],
    ] as [$icon, $text])
    <tr>
        <td style="padding: 8px 0; width: 24px; color: #C4714A; vertical-align: top; font-size: 14px; border: none;">{{ $icon }}</td>
        <td style="padding: 8px 0; font-size: 14px; color: #4a4a48; border: none;">{{ $text }}</td>
    </tr>
    @endforeach
</table>

<div style="text-align: center; margin-top: 32px;">
    <a href="{{ url('/') }}" class="btn">Explorar la colección</a>
</div>
@endsection
