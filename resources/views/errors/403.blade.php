@extends('errors.layout')

@section('code', '403')
@section('accent', '#4D7C5F')
@section('title', 'Acceso prohibido')
@section('description', 'No tienes permiso para ver este contenido. Si crees que es un error, inicia sesión con la cuenta correcta.')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <rect x="20" y="36" width="40" height="28" rx="3" class="icon-body"/>
    <path d="M28 36V26a12 12 0 0124 0v10" class="icon-arc"/>
    <circle cx="40" cy="50" r="4" class="icon-keyhole"/>
    <line x1="40" y1="54" x2="40" y2="58" class="icon-keyline"/>
</svg>
@endsection

@section('extra_styles')
<style>
    :root { --accent: #4D7C5F; }

    .icon-body    { stroke-dasharray: 140; stroke-dashoffset: 140; animation: drawLine 0.7s 0.3s ease forwards; }
    .icon-arc     { stroke-dasharray: 80; stroke-dashoffset: 80; animation: drawLine 0.6s 0.7s ease forwards; }
    .icon-keyhole { stroke-dasharray: 30; stroke-dashoffset: 30; animation: drawLine 0.4s 1s ease forwards; }
    .icon-keyline { stroke-dasharray: 10; stroke-dashoffset: 10; animation: drawLine 0.3s 1.2s ease forwards; }
    @keyframes drawLine { to { stroke-dashoffset: 0; } }

    /* Sacudir el candado */
    .error-icon svg { animation: shakeLock 4s ease-in-out infinite; }
    @keyframes shakeLock {
        0%, 70%, 100% { transform: rotate(0deg); }
        72% { transform: rotate(-8deg); }
        74% { transform: rotate(8deg); }
        76% { transform: rotate(-6deg); }
        78% { transform: rotate(6deg); }
        80% { transform: rotate(0deg); }
    }

    /* Barras de "acceso denegado" */
    .access-bars {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }
    .access-bar {
        position: absolute;
        top: -100%;
        width: 2px;
        height: 100vh;
        background: linear-gradient(to bottom, transparent, rgba(77,124,95,0.08), transparent);
        animation: scanBar linear infinite;
    }
</style>
@endsection

@section('actions')
<a href="{{ route('login') }}" class="btn-error-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
    Iniciar sesión
</a>
<a href="{{ route('home') }}" class="btn-error-ghost">
    Volver a la tienda
</a>
@endsection

@push('scripts')
<script>
const container = document.createElement('div');
container.className = 'access-bars';
document.body.appendChild(container);
for (let i = 0; i < 8; i++) {
    const bar = document.createElement('div');
    bar.className = 'access-bar';
    bar.style.left = (5 + i * 13) + '%';
    bar.style.animationDuration = (8 + i * 2) + 's';
    bar.style.animationDelay = (-i * 1.5) + 's';
    container.appendChild(bar);
}
const s = document.createElement('style');
s.textContent = `@keyframes scanBar {
    0%   { top: -100%; opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}`;
document.head.appendChild(s);
</script>
@endpush
