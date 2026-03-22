@extends('errors.layout')

@section('code', '429')
@section('accent', '#D94035')
@section('title', 'Demasiadas solicitudes')
@section('description', 'Estás haciendo demasiadas peticiones en poco tiempo. Espera unos segundos y vuelve a intentarlo.')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    {{-- Relámpago --}}
    <path d="M46 8 L28 44 H42 L34 72 L58 32 H44 Z" class="icon-bolt"/>
</svg>
@endsection

@section('extra_styles')
<style>
    :root { --accent: #D94035; }

    .icon-bolt {
        stroke-dasharray: 200;
        stroke-dashoffset: 200;
        animation: drawLine 0.6s 0.3s ease forwards, boltFlash 2s 1s ease-in-out infinite;
        fill: rgba(217,64,53,0.15);
    }
    @keyframes drawLine  { to { stroke-dashoffset: 0; } }
    @keyframes boltFlash {
        0%, 80%, 100% { filter: drop-shadow(0 0 0px #D94035); fill: rgba(217,64,53,0.15); }
        85%           { filter: drop-shadow(0 0 20px #D94035); fill: rgba(217,64,53,0.4); }
    }

    /* Contador de cuenta regresiva */
    .countdown-wrap {
        margin: 1.5rem auto 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        opacity: 0;
        animation: fadeSlideUp 0.5s 0.6s ease forwards;
    }
    .countdown-num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 300;
        color: #D94035;
        min-width: 3rem;
        text-align: center;
        line-height: 1;
    }
    .countdown-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #9E9589;
    }

    /* Barras de velocidad */
    .speed-bars {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        height: 4px;
        display: flex;
        gap: 2px;
        z-index: 5;
        pointer-events: none;
    }
    .speed-bar {
        flex: 1;
        background: rgba(217,64,53,0.3);
        animation: speedPulse linear infinite;
        transform-origin: bottom;
    }
</style>
@endsection

@section('actions')
<div style="text-align:center; width:100%;">
    <div class="countdown-wrap">
        <div>
            <div class="countdown-num" id="countdown">30</div>
            <div class="countdown-label">segundos</div>
        </div>
    </div>
</div>
<a href="javascript:location.reload()" class="btn-error-primary" id="retry-btn" style="pointer-events:none; opacity:0.4;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Reintentar
</a>
<a href="{{ route('home') }}" class="btn-error-ghost">Inicio</a>
@endsection

@push('scripts')
<script>
// Cuenta regresiva
let secs = 30;
const display = document.getElementById('countdown');
const btn     = document.getElementById('retry-btn');
const timer   = setInterval(() => {
    secs--;
    display.textContent = secs;
    if (secs <= 0) {
        clearInterval(timer);
        btn.style.pointerEvents = 'auto';
        btn.style.opacity = '1';
        display.textContent = '✓';
    }
}, 1000);

// Barras de velocidad
const container = document.createElement('div');
container.className = 'speed-bars';
document.body.appendChild(container);
for (let i = 0; i < 40; i++) {
    const bar = document.createElement('div');
    bar.className = 'speed-bar';
    bar.style.animationDuration = (0.3 + Math.random() * 0.5) + 's';
    bar.style.animationDelay    = (-Math.random() * 0.5) + 's';
    container.appendChild(bar);
}
const s = document.createElement('style');
s.textContent = `@keyframes speedPulse {
    0%,100% { transform: scaleY(1); opacity: 0.3; }
    50%     { transform: scaleY(4); opacity: 0.8; }
}`;
document.head.appendChild(s);
</script>
@endpush
