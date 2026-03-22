@extends('errors.layout')

@section('code', '404')
@section('accent', '#B85C38')
@section('title', 'Página no encontrada')
@section('description', 'La página que buscas no existe, fue movida o simplemente se perdió en el universo. Pero tu estilo sí tiene destino.')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    {{-- Lupa con estrella --}}
    <circle cx="34" cy="34" r="18" class="icon-search"/>
    <line x1="47" y1="47" x2="66" y2="66" class="icon-handle"/>
    <line x1="27" y1="34" x2="41" y2="34" class="icon-inner"/>
    <line x1="34" y1="27" x2="34" y2="41" class="icon-inner"/>
</svg>
@endsection

@section('extra_styles')
<style>
    .icon-search  { animation: rotateMag 8s linear infinite; transform-origin: 34px 34px; }
    .icon-handle  { stroke-dasharray: 27; stroke-dashoffset: 27; animation: drawLine 0.8s 0.5s ease forwards; }
    .icon-inner   { stroke-dasharray: 14; stroke-dashoffset: 14; animation: drawLine 0.5s 0.8s ease forwards; }
    @keyframes rotateMag {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes drawLine {
        to { stroke-dashoffset: 0; }
    }

    /* Letras que flotan alrededor */
    .floating-letters {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }
    .fl-letter {
        position: absolute;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem;
        color: rgba(184,92,56,0.15);
        animation: floatLetter linear infinite;
        user-select: none;
    }
</style>
@endsection

@section('actions')
<a href="{{ route('home') }}" class="btn-error-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Volver al inicio
</a>
<a href="javascript:history.back()" class="btn-error-ghost">
    ← Página anterior
</a>
@endsection

@push('scripts')
<script>
// Letras flotantes de moda
const words = ['MODA','ESTILO','FAMILIA','LIMA','PERÚ','TALLA','COLOR','ENVÍO','NUEVO'];
const container = document.createElement('div');
container.className = 'floating-letters';
document.body.appendChild(container);

words.forEach((word, i) => {
    const el = document.createElement('span');
    el.className = 'fl-letter';
    el.textContent = word;
    el.style.left   = (10 + Math.random() * 80) + '%';
    el.style.top    = (Math.random() * 100) + '%';
    el.style.fontSize = (0.7 + Math.random() * 0.8) + 'rem';
    el.style.animationDuration = (15 + Math.random() * 20) + 's';
    el.style.animationDelay   = (-Math.random() * 20) + 's';
    el.style.setProperty('--drift', (Math.random() > 0.5 ? 1 : -1) * (20 + Math.random() * 40) + 'px');
    container.appendChild(el);
});

const styleEl = document.createElement('style');
styleEl.textContent = `
@keyframes floatLetter {
    0%   { transform: translateY(100vh) translateX(0); opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 0.8; }
    100% { transform: translateY(-20vh) translateX(var(--drift,20px)); opacity: 0; }
}`;
document.head.appendChild(styleEl);
</script>
@endpush
