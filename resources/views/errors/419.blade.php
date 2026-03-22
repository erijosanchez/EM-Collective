@extends('errors.layout')

@section('code', '419')
@section('accent', '#9E9589')
@section('title', 'Sesión expirada')
@section('description', 'Tu sesión ha expirado por inactividad o el formulario tardó demasiado. Vuelve atrás y vuelve a intentarlo.')

@section('icon')
<svg viewBox="0 0 80 80" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="40" cy="40" r="24" class="icon-clock-face"/>
    <line x1="40" y1="40" x2="40" y2="22" class="icon-hour"/>
    <line x1="40" y1="40" x2="54" y2="44" class="icon-min"/>
    <circle cx="40" cy="40" r="2" fill="currentColor" stroke="none"/>
    <path d="M60 16 L68 12 M60 16 L64 24" class="icon-arrow" stroke-width="1.5"/>
</svg>
@endsection

@section('extra_styles')
<style>
    :root { --accent: #9E9589; }

    .icon-clock-face { stroke-dasharray: 151; stroke-dashoffset: 151; animation: drawLine 0.8s 0.2s ease forwards; }
    .icon-arrow      { stroke-dasharray: 20; stroke-dashoffset: 20; animation: drawLine 0.4s 0.8s ease forwards; }

    /* Manecilla hora: rota */
    .icon-hour {
        transform-origin: 40px 40px;
        animation: rotateHour 8s linear infinite;
    }
    /* Manecilla minutos: rota más rápido */
    .icon-min {
        transform-origin: 40px 40px;
        animation: rotateMin 2s linear infinite;
    }
    @keyframes rotateHour { to { transform: rotate(360deg); } }
    @keyframes rotateMin  { to { transform: rotate(360deg); } }

    /* Partículas de arena / tiempo */
    .sand-particle {
        position: fixed;
        width: 3px;
        height: 3px;
        background: rgba(158,149,137,0.4);
        border-radius: 50%;
        pointer-events: none;
        z-index: 1;
        animation: sandFall linear infinite;
    }
</style>
@endsection

@section('actions')
<a href="javascript:history.back()" class="btn-error-primary">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Volver e intentar de nuevo
</a>
<a href="{{ route('home') }}" class="btn-error-ghost">
    Ir al inicio
</a>
@endsection

@push('scripts')
<script>
// Partículas de arena cayendo
for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'sand-particle';
    p.style.left = Math.random() * 100 + '%';
    p.style.top  = -10 + 'px';
    p.style.width  = (1 + Math.random() * 3) + 'px';
    p.style.height = (1 + Math.random() * 3) + 'px';
    p.style.animationDuration  = (3 + Math.random() * 6) + 's';
    p.style.animationDelay     = (-Math.random() * 8) + 's';
    document.body.appendChild(p);
}
const s = document.createElement('style');
s.textContent = `@keyframes sandFall {
    from { transform: translateY(-10px); opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 0.5; }
    to   { transform: translateY(100vh); opacity: 0; }
}`;
document.head.appendChild(s);
</script>
@endpush
