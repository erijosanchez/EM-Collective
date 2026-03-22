@extends('errors.layout')

@section('code', '500')
@section('accent', '#D94035')
@section('title', 'Error del servidor')
@section('description', 'Algo explotó en nuestros servidores. Nuestro equipo ya fue notificado y lo estamos resolviendo. Vuelve en unos minutos.')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M40 12 L68 60 H12 Z" class="icon-tri"/>
    <line x1="40" y1="30" x2="40" y2="46" class="icon-line"/>
    <circle cx="40" cy="52" r="2" class="icon-dot" fill="currentColor" stroke="none"/>
</svg>
@endsection

@section('extra_styles')
<style>
    :root { --accent: #D94035; }

    .icon-tri  { stroke-dasharray: 170; stroke-dashoffset: 170; animation: drawLine 0.8s 0.3s ease forwards; }
    .icon-line { stroke-dasharray: 16; stroke-dashoffset: 16; animation: drawLine 0.4s 0.9s ease forwards; }
    .icon-dot  { animation: blinkDot 1s 1.1s ease forwards, blinkDot 1s 1.5s ease infinite; opacity: 0; }
    @keyframes drawLine { to { stroke-dashoffset: 0; } }
    @keyframes blinkDot {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.2; }
    }

    /* Código roto que cae */
    .code-rain {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
        font-family: 'Courier New', monospace;
        font-size: 0.65rem;
    }
    .code-col {
        position: absolute;
        top: -100%;
        color: rgba(217,64,53,0.12);
        line-height: 1.4;
        animation: rainFall linear infinite;
        white-space: nowrap;
        writing-mode: vertical-lr;
        letter-spacing: 0.1em;
    }
</style>
@endsection

@section('actions')
<a href="javascript:location.reload()" class="btn-error-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Reintentar
</a>
<a href="{{ route('home') }}" class="btn-error-ghost">
    Volver al inicio
</a>
@endsection

@push('scripts')
<script>
const container = document.createElement('div');
container.className = 'code-rain';
document.body.appendChild(container);

const chars = '500 ERROR NULL UNDEFINED EXCEPTION FATAL SERVER CRASH 0x00 0xFF STACK OVERFLOW';
for (let i = 0; i < 12; i++) {
    const col = document.createElement('div');
    col.className = 'code-col';
    col.style.left = (i * 9) + '%';
    col.style.animationDuration = (6 + Math.random() * 8) + 's';
    col.style.animationDelay = (-Math.random() * 10) + 's';
    let text = '';
    for (let j = 0; j < 20; j++) {
        const word = chars.split(' ')[Math.floor(Math.random() * chars.split(' ').length)];
        text += word + ' ';
    }
    col.textContent = text;
    container.appendChild(col);
}

const s = document.createElement('style');
s.textContent = `@keyframes rainFall {
    from { top: -100%; }
    to   { top: 110%; }
}`;
document.head.appendChild(s);
</script>
@endpush
