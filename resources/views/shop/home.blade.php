@extends('layouts.shop')

@section('title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))
@section('description', \App\Models\Setting::get('seo_home_description', ''))

@section('head')
<style>
    /* ── Scroll Reveal ── */
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-left { opacity: 0; transform: translateX(-30px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal-left.visible { opacity: 1; transform: translateX(0); }
    .reveal-scale { opacity: 0; transform: scale(0.95); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal-scale.visible { opacity: 1; transform: scale(1); }

    /* Stagger delays para grids */
    .stagger-1 { transition-delay: 0.05s; }
    .stagger-2 { transition-delay: 0.12s; }
    .stagger-3 { transition-delay: 0.19s; }
    .stagger-4 { transition-delay: 0.26s; }

    /* ── Hero Carrusel ── */
    .hero-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.8s ease; }
    .hero-slide.active { opacity: 1; }

    /* Animación parallax en imagen del hero */
    .hero-img { transition: transform 8s ease-out; }
    .hero-img.zoomed { transform: scale(1.06); }

    /* ── Indicadores del carrusel ── */
    .carousel-dot { width: 24px; height: 2px; background: rgba(245,240,232,0.4); transition: all 0.4s ease; cursor: pointer; }
    .carousel-dot.active { width: 48px; background: #F5F0E8; }

    /* Animación de entrada del texto del hero */
    @keyframes heroTextIn {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-text-anim { animation: heroTextIn 0.7s ease forwards; }
    .hero-text-anim-delay { animation: heroTextIn 0.7s 0.15s ease forwards; opacity: 0; }
    .hero-text-anim-delay2 { animation: heroTextIn 0.7s 0.3s ease forwards; opacity: 0; }

    /* ── Product card hover line ── */
    .product-line { height: 2px; background: #2563EB; transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease; }
    .group:hover .product-line { transform: scaleX(1); }

    /* Hover card elevación */
    .product-card-wrap { transition: transform 0.3s ease; }
    .product-card-wrap:hover { transform: translateY(-4px); }

    /* ── Barra animada trust badges ── */
    @keyframes badgePop {
        from { opacity: 0; transform: translateY(20px) scale(0.9); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .badge-item { opacity: 0; }
    .badge-item.visible { animation: badgePop 0.5s ease forwards; }

    /* ── Línea decorativa animada ── */
    @keyframes lineGrow {
        from { transform: scaleX(0); }
        to   { transform: scaleX(1); }
    }
    .section-line { transform: scaleX(0); transform-origin: left; transition: transform 0.8s ease; }
    .section-line.visible { transform: scaleX(1); }

    /* ── Marquee en ofertas ── */
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .marquee-track { animation: marquee 18s linear infinite; display: flex; white-space: nowrap; }
    .marquee-track:hover { animation-play-state: paused; }
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════
     HERO — Carrusel multi-slide
══════════════════════════════════════ --}}
@php $herobanners = $banners->where('position', 'hero')->values(); @endphp

<section class="relative h-[88vh] min-h-[520px] bg-carbon overflow-hidden"
         x-data="heroCarousel({{ $herobanners->count() }})"
         @mouseenter="pause()" @mouseleave="resume()">

    {{-- Slides --}}
    @forelse($herobanners as $i => $hb)
    <div class="hero-slide" :class="{ active: current === {{ $i }} }">
        @if($hb->image)
        <img src="{{ asset('storage/' . $hb->image) }}"
             alt="{{ $hb->title }}"
             class="hero-img absolute inset-0 w-full h-full object-cover opacity-60"
             :class="{ zoomed: current === {{ $i }} }">
        @else
        <div class="absolute inset-0 bg-gradient-to-br from-carbon to-stone/80"></div>
        @endif

        {{-- Overlay degradado --}}
        <div class="absolute inset-0 bg-gradient-to-r from-carbon/60 via-carbon/20 to-transparent"></div>

        {{-- Texto del slide --}}
        @php
            $hAlign      = $hb->text_align     ?? 'left';
            $vAlign      = $hb->text_valign    ?? 'middle';
            $fontFam     = $hb->font_family     ?? 'serif';
            $txtColor    = $hb->text_color;
            $bgHex       = $hb->text_bg_color;
            $bgOp        = (int) ($hb->text_bg_opacity ?? 0);
            $valignClass = match($vAlign) {
                'top'    => 'items-start pt-20',
                'bottom' => 'items-end pb-20',
                default  => 'items-center',
            };
            $alignClass  = match($hAlign) {
                'center' => 'mx-auto text-center',
                'right'  => 'ml-auto text-right',
                default  => 'text-left',
            };
            $fontClass   = $fontFam === 'sans' ? 'font-sans' : 'font-serif';
            $bgStyle     = '';
            if ($bgHex && $bgOp > 0) {
                $r = hexdec(substr($bgHex, 1, 2));
                $g = hexdec(substr($bgHex, 3, 2));
                $b = hexdec(substr($bgHex, 5, 2));
                $a = round($bgOp / 100, 2);
                $bgStyle = "background:rgba($r,$g,$b,$a);padding:1.5rem 2rem;border-radius:0.75rem;backdrop-filter:blur(2px)";
            }
        @endphp
        <div class="absolute inset-0 flex {{ $valignClass }}">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 w-full">
                <div class="max-w-xl {{ $alignClass }}" @if($bgStyle) style="{{ $bgStyle }}" @endif>
                    <template x-if="current === {{ $i }}">
                        <div>
                            <p class="text-terracota text-xs uppercase tracking-widest mb-4 hero-text-anim">Nueva Colección</p>
                            <h1 class="{{ $fontClass }} text-4xl sm:text-5xl lg:text-7xl font-light leading-tight mb-6 hero-text-anim-delay {{ $txtColor ? '' : 'text-cream' }}"
                                @if($txtColor) style="color: {{ $txtColor }}" @endif>
                                {{ $hb->title }}<br>
                                @if($hb->subtitle)<em class="italic">{{ $hb->subtitle }}</em>@endif
                            </h1>
                            @if($hb->button_url)
                            <a href="{{ $hb->button_url }}"
                               class="btn-primary !border-cream !text-cream hover:!bg-terracota hover:!border-terracota hero-text-anim-delay2">
                                {{ $hb->button_text ?? 'Explorar' }}
                            </a>
                            @else
                            <a href="{{ route('category.show', 'mujer') }}"
                               class="btn-primary !border-cream !text-cream hover:!bg-terracota hover:!border-terracota hero-text-anim-delay2">
                                Explorar Colección
                            </a>
                            @endif
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- Fallback sin banners --}}
    <div class="hero-slide active">
        <div class="absolute inset-0 bg-gradient-to-r from-carbon to-stone/80"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 w-full">
                <div class="max-w-xl animate-fade-up">
                    <p class="text-terracota text-xs uppercase tracking-widest mb-4">Nueva Colección</p>
                    <h1 class="font-serif text-5xl sm:text-7xl text-cream font-light leading-tight mb-6">
                        Moda que<br><em class="italic">te define</em>
                    </h1>
                    <a href="{{ route('category.show', 'mujer') }}" class="btn-primary !border-cream !text-cream hover:!bg-terracota hover:!border-terracota">
                        Explorar Colección
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    {{-- Controles de navegación (solo si hay 2+) --}}
    @if($herobanners->count() > 1)
    {{-- Flecha izquierda --}}
    <button @click="prev()" aria-label="Anterior"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center
                   border border-cream/40 text-cream hover:bg-cream/20 transition-all duration-200 rounded-full">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    {{-- Flecha derecha --}}
    <button @click="next()" aria-label="Siguiente"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center
                   border border-cream/40 text-cream hover:bg-cream/20 transition-all duration-200 rounded-full">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Indicadores / dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
        @foreach($herobanners as $i => $hb)
        <button @click="goTo({{ $i }})" aria-label="Slide {{ $i + 1 }}"
                class="carousel-dot" :class="{ active: current === {{ $i }} }"></button>
        @endforeach
    </div>

    {{-- Barra de progreso --}}
    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-cream/10 z-20">
        <div class="h-full bg-terracota transition-none"
             :style="`width: ${progress}%; transition: width ${progress === 0 ? '0' : '5'}s linear`"></div>
    </div>
    @endif
</section>

<script>
function heroCarousel(total) {
    return {
        current: 0,
        total: total,
        timer: null,
        progress: 0,
        progressTimer: null,
        init() {
            if (this.total > 1) setTimeout(() => this.startAuto(), 100);
        },
        startAuto() {
            this.progress = 0;
            clearInterval(this.progressTimer);
            this.progressTimer = setInterval(() => {
                this.progress += (100 / 500); // ~5s
                if (this.progress >= 100) { this.next(); }
            }, 10);
        },
        goTo(index) {
            this.current = index;
            this.progress = 0;
            clearInterval(this.progressTimer);
            if (this.total > 1) this.startAuto();
        },
        next() { this.goTo((this.current + 1) % this.total); },
        prev() { this.goTo((this.current - 1 + this.total) % this.total); },
        pause() { clearInterval(this.progressTimer); },
        resume() { if (this.total > 1) this.startAuto(); },
    }
}
</script>


{{-- ══════════════════════════════════════
     CATEGORÍAS PRINCIPALES — Layout asimétrico
══════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">

    {{-- Encabezado animado --}}
    <div class="flex items-center gap-4 mb-8 reveal">
        <div class="h-px flex-1 bg-stone/20 section-line"></div>
        <span class="text-stone text-xs uppercase tracking-widest">Explora</span>
        <div class="h-px flex-1 bg-stone/20 section-line"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-auto md:h-[600px]">

        {{-- Mujer — Grande izquierda --}}
        @php $mujer = $mainCategories->firstWhere('slug', 'mujer'); @endphp
        <div class="md:col-span-2 relative img-hover bg-stone/20 overflow-hidden reveal-left">
            @if($mujer?->image)
            <img src="{{ asset('storage/' . $mujer->image) }}" alt="Mujer"
                 class="w-full h-full object-cover min-h-[350px]">
            @else
            <div class="w-full h-full min-h-[350px] bg-gradient-to-br from-stone/30 to-stone/10"></div>
            @endif
            <div class="absolute inset-0 bg-carbon/30 flex flex-col justify-end p-8">
                <h2 class="font-serif text-4xl text-cream font-light mb-2">Mujer</h2>
                <p class="text-cream/80 text-sm mb-4">La elegancia en cada pieza</p>
                <a href="{{ route('category.show', 'mujer') }}" class="btn-primary !text-cream !border-cream w-fit">
                    Ver Colección
                </a>
            </div>
        </div>

        {{-- Hombre + Ofertas apilados a la derecha --}}
        <div class="flex flex-col gap-4">
            @php $hombre = $mainCategories->firstWhere('slug', 'hombre'); @endphp
            <div class="relative img-hover bg-stone/20 overflow-hidden flex-1 reveal" style="transition-delay:0.15s">
                @if($hombre?->image)
                <img src="{{ asset('storage/' . $hombre->image) }}" alt="Hombre"
                     class="w-full h-full object-cover min-h-[200px]">
                @else
                <div class="w-full min-h-[200px] bg-gradient-to-br from-carbon/80 to-carbon/60"></div>
                @endif
                <div class="absolute inset-0 bg-carbon/40 flex flex-col justify-end p-6">
                    <h2 class="font-serif text-2xl text-cream font-light mb-3">Hombre</h2>
                    <a href="{{ route('category.show', 'hombre') }}" class="btn-outline !text-cream !border-cream/60 w-fit text-xs">
                        Ver Todo
                    </a>
                </div>
            </div>

            <div class="relative bg-terracota overflow-hidden flex-1 p-6 flex flex-col justify-between min-h-[180px] reveal" style="transition-delay:0.25s">
                <p class="font-serif text-8xl font-light text-cream/20 absolute -bottom-4 -right-2 select-none">50%</p>
                <div>
                    <p class="text-cream/80 text-xs uppercase tracking-widest mb-1">Hasta</p>
                    <h2 class="font-serif text-4xl text-cream font-light">50% OFF</h2>
                    <p class="text-cream/80 text-sm mt-1">En productos seleccionados</p>
                </div>
                <a href="{{ route('product.search') }}?on_sale=1" class="btn-outline !text-cream !border-cream w-fit mt-4">
                    Ver Ofertas
                </a>
            </div>
        </div>
    </div>

    {{-- Niños como banner full width --}}
    @php $ninos = $mainCategories->firstWhere('slug', 'ninos'); @endphp
    <div class="mt-4 relative img-hover bg-stone/20 overflow-hidden h-48 reveal">
        @if($ninos?->image)
        <img src="{{ asset('storage/' . $ninos->image) }}" alt="Niños"
             class="w-full h-full object-cover">
        @else
        <div class="w-full h-full bg-cream/50"></div>
        @endif
        <div class="absolute inset-0 bg-carbon/30 flex items-center px-8">
            <div>
                <h2 class="font-serif text-3xl text-cream font-light mb-2">Niños</h2>
                <a href="{{ route('category.show', 'ninos') }}" class="text-cream text-xs uppercase tracking-widest underline">
                    Explorar
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     BANNER MEDIO
══════════════════════════════════════ --}}
@php $midBanner = $banners->where('position', 'mid_home')->first(); @endphp
@if($midBanner)
<section class="relative py-24 bg-carbon text-cream overflow-hidden reveal-scale">
    @if($midBanner->image)
    <img src="{{ asset('storage/' . $midBanner->image) }}" alt="{{ $midBanner->title }}"
         class="absolute inset-0 w-full h-full object-cover opacity-40">
    @endif
    {{-- Gradiente lateral decorativo --}}
    <div class="absolute inset-0 bg-gradient-to-r from-carbon/60 via-transparent to-carbon/60 pointer-events-none"></div>
    @php $align = $midBanner->text_align ?? 'center'; @endphp
    <div class="relative z-10 max-w-3xl mx-auto px-6 {{ $align === 'center' ? 'text-center' : ($align === 'right' ? 'text-right' : 'text-left') }}">
        @if($midBanner->subtitle)
        <p class="text-terracota text-xs uppercase tracking-widest mb-4">{{ $midBanner->subtitle }}</p>
        @endif
        <h2 class="font-serif text-5xl sm:text-6xl font-light mb-6 leading-tight">{{ $midBanner->title }}</h2>
        @if($midBanner->button_url)
        <a href="{{ $midBanner->button_url }}" class="btn-primary !border-cream !text-cream">
            {{ $midBanner->button_text ?? 'Ver más' }}
        </a>
        @endif
    </div>
</section>
@endif


{{-- ══════════════════════════════════════
     MARQUEE — Texto corrido decorativo
══════════════════════════════════════ --}}
<div class="bg-terracota py-3 overflow-hidden select-none">
    <div class="marquee-track text-cream text-xs uppercase tracking-widest font-sans font-medium">
        @for($m = 0; $m < 2; $m++)
        <span class="px-8">Nueva Colección</span>
        <span class="px-4">·</span>
        <span class="px-8">Envío Rápido</span>
        <span class="px-4">·</span>
        <span class="px-8">Pago Seguro</span>
        <span class="px-4">·</span>
        <span class="px-8">Hasta 50% OFF</span>
        <span class="px-4">·</span>
        <span class="px-8">Moda Exclusiva</span>
        <span class="px-4">·</span>
        <span class="px-8">EM Collective</span>
        <span class="px-4">·</span>
        @endfor
    </div>
</div>


{{-- ══════════════════════════════════════
     PRODUCTOS DESTACADOS
══════════════════════════════════════ --}}
@if($featuredProducts->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="flex items-end justify-between mb-10 reveal">
        <div>
            <div class="w-8 h-0.5 bg-terracota mb-3"></div>
            <p class="text-terracota text-xs uppercase tracking-widest mb-2">Selección Editorial</p>
            <h2 class="font-serif text-4xl font-light">Productos Destacados</h2>
        </div>
        <a href="{{ route('product.search') }}?featured=1" class="text-xs uppercase tracking-widest text-stone hover:text-carbon underline">
            Ver todos
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($featuredProducts as $idx => $product)
        <div class="product-card-wrap reveal stagger-{{ ($idx % 4) + 1 }}">
            @include('shop._product-card', ['product' => $product])
            <div class="product-line mt-2"></div>
        </div>
        @endforeach
    </div>
</section>
@endif


{{-- ══════════════════════════════════════
     PRODUCTOS EN OFERTA
══════════════════════════════════════ --}}
@if($saleProducts->count())
<section class="bg-carbon py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10 reveal">
            <div>
                <div class="w-8 h-0.5 bg-terracota mb-3"></div>
                <p class="text-terracota text-xs uppercase tracking-widest mb-2">Precios Irresistibles</p>
                <h2 class="font-serif text-4xl font-light text-cream">En Oferta</h2>
            </div>
            <a href="{{ route('product.search') }}?on_sale=1" class="text-xs uppercase tracking-widest text-stone hover:text-cream underline">
                Ver todas
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($saleProducts as $idx => $product)
            <div class="product-card-wrap reveal stagger-{{ ($idx % 4) + 1 }}">
                @include('shop._product-card', ['product' => $product, 'dark' => true])
                <div class="product-line mt-2"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════
     TRUST BADGES
══════════════════════════════════════ --}}
<section class="border-t border-stone/20 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([
                ['M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'Envío Rápido', 'A todo el Perú en 1-7 días'],
                ['M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'Pago Seguro', 'Mercado Pago, Yape y Plin'],
                ['M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'Contra Entrega', 'Paga al recibir tu pedido'],
                ['M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'Cambios Fáciles', '30 días para cambiar talla o color'],
            ] as $i => $badge)
            <div class="badge-item stagger-{{ $i + 1 }} group">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full border border-stone/20 flex items-center justify-center group-hover:border-terracota group-hover:bg-terracota/5 transition-all duration-300">
                    <svg class="w-7 h-7 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $badge[0] }}"/>
                    </svg>
                </div>
                <h4 class="font-serif text-lg mb-1">{{ $badge[1] }}</h4>
                <p class="text-stone text-xs">{{ $badge[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════
     JS — Scroll Reveal con IntersectionObserver
══════════════════════════════════════ --}}
<script>
(function () {
    const opts = { threshold: 0.12, rootMargin: '0px 0px -40px 0px' };

    // Reveal genérico
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, opts);

    document.querySelectorAll('.reveal, .reveal-left, .reveal-scale, .section-line').forEach(el => observer.observe(el));

    // Trust badges con stagger
    const badgeObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const delay = e.target.classList.contains('stagger-1') ? 0 :
                              e.target.classList.contains('stagger-2') ? 100 :
                              e.target.classList.contains('stagger-3') ? 200 : 300;
                setTimeout(() => e.target.classList.add('visible'), delay);
                badgeObs.unobserve(e.target);
            }
        });
    }, opts);

    document.querySelectorAll('.badge-item').forEach(el => badgeObs.observe(el));
})();
</script>

@endsection
