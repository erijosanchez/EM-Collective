@extends('errors.layout')

@section('code', '503')
@section('accent', '#B85C38')
@section('title', 'En mantenimiento')
@section('description', 'Estamos mejorando EM Collective para darte una mejor experiencia. Volvemos muy pronto. ¡Vale la pena esperar!')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    {{-- Engranaje --}}
    <circle cx="40" cy="40" r="10" class="icon-center"/>
    <path d="M40 16v6M40 58v6M16 40h6M58 40h6
             M23.5 23.5l4.2 4.2M52.3 52.3l4.2 4.2
             M56.5 23.5l-4.2 4.2M27.7 52.3l-4.2 4.2" class="icon-spokes"/>
    <circle cx="40" cy="40" r="18" class="icon-outer" stroke-dasharray="6 4"/>
</svg>
@endsection

@section('extra_styles')
<style>
    .icon-center  { stroke-dasharray: 63; stroke-dashoffset: 63; animation: drawLine 0.5s 0.3s ease forwards; }
    .icon-spokes  { stroke-dasharray: 200; stroke-dashoffset: 200; animation: drawLine 0.6s 0.6s ease forwards; }
    .icon-outer   { animation: rotateGear 8s linear infinite; transform-origin: 40px 40px; }
    @keyframes drawLine   { to { stroke-dashoffset: 0; } }
    @keyframes rotateGear { to { transform: rotate(360deg); } }

    /* El icono completo también gira lento */
    .error-icon { animation: none !important; }
    .error-icon svg {
        animation: rotateGearSvg 12s linear infinite !important;
        filter: none;
    }
    @keyframes rotateGearSvg {
        to { transform: rotate(360deg); }
    }

    /* Barra de progreso indeterminada */
    .maintenance-progress {
        margin: 1.5rem auto 0;
        width: 200px;
        height: 2px;
        background: rgba(245,241,235,0.1);
        overflow: hidden;
        opacity: 0;
        animation: fadeSlideUp 0.5s 0.6s ease forwards;
    }
    .maintenance-progress-bar {
        height: 100%;
        width: 40%;
        background: linear-gradient(90deg, transparent, #B85C38, transparent);
        animation: progressSlide 2s ease-in-out infinite;
    }
    .maintenance-eta {
        font-size: 0.75rem;
        color: #9E9589;
        margin-top: 0.75rem;
        letter-spacing: 0.05em;
        opacity: 0;
        animation: fadeSlideUp 0.5s 0.7s ease forwards;
    }
    @keyframes progressSlide {
        0%   { transform: translateX(-150%); }
        100% { transform: translateX(350%); }
    }
</style>
@endsection

@section('actions')
<div style="text-align:center; width:100%;">
    <div class="maintenance-progress">
        <div class="maintenance-progress-bar"></div>
    </div>
    <p class="maintenance-eta">Trabajando en mejoras para ti...</p>
</div>
<a href="javascript:location.reload()" class="btn-error-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Verificar disponibilidad
</a>
@if(\App\Models\Setting::get('store_whatsapp'))
<a href="https://wa.me/{{ preg_replace('/\D/', '', \App\Models\Setting::get('store_whatsapp')) }}"
   target="_blank" class="btn-error-ghost">
    Contáctanos por WhatsApp
</a>
@endif
@endsection
