@extends('layouts.shop')

@section('title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))
@section('description', \App\Models\Setting::get('seo_home_description', ''))

@section('content')

{{-- HERO --}}
@php $heroBanner = $banners->where('position', 'hero')->first(); @endphp
<section class="relative h-[85vh] min-h-[500px] bg-carbon overflow-hidden">
    @if($heroBanner && $heroBanner->image)
    <img src="{{ asset('storage/' . $heroBanner->image) }}" alt="{{ $heroBanner->title }}"
         class="absolute inset-0 w-full h-full object-cover opacity-60">
    @else
    <div class="absolute inset-0 bg-gradient-to-r from-carbon to-stone/80"></div>
    @endif

    <div class="absolute inset-0 flex items-center">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 w-full">
            <div class="max-w-xl animate-fade-up">
                <p class="text-terracota text-xs uppercase tracking-widest mb-4">Nueva Colección</p>
                <h1 class="font-serif text-5xl sm:text-7xl text-cream font-light leading-tight mb-6">
                    {{ $heroBanner?->title ?? 'Moda que' }}<br>
                    <em class="italic">{{ $heroBanner?->subtitle ?? 'te define' }}</em>
                </h1>
                @if($heroBanner?->button_url)
                <a href="{{ $heroBanner->button_url }}" class="btn-primary !border-cream !text-cream hover:!bg-terracota hover:!border-terracota">
                    {{ $heroBanner->button_text ?? 'Explorar' }}
                </a>
                @else
                <a href="{{ route('category.show', 'mujer') }}" class="btn-primary !border-cream !text-cream hover:!bg-terracota hover:!border-terracota">
                    Explorar Colección
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- CATEGORÍAS PRINCIPALES — Layout asimétrico tipo revista --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-auto md:h-[600px]">

        {{-- Mujer — Grande izquierda --}}
        @php $mujer = $mainCategories->firstWhere('slug', 'mujer'); @endphp
        <div class="md:col-span-2 relative img-hover bg-stone/20 overflow-hidden">
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

        {{-- Hombre + Niños apilados a la derecha --}}
        <div class="flex flex-col gap-4">
            {{-- Hombre --}}
            @php $hombre = $mainCategories->firstWhere('slug', 'hombre'); @endphp
            <div class="relative img-hover bg-stone/20 overflow-hidden flex-1">
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

            {{-- Ofertas con tipografía grande --}}
            <div class="relative bg-terracota overflow-hidden flex-1 p-6 flex flex-col justify-between min-h-[180px]">
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
    <div class="mt-4 relative img-hover bg-stone/20 overflow-hidden h-48">
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

{{-- BANNER MEDIO --}}
@php $midBanner = $banners->where('position', 'mid_home')->first(); @endphp
@if($midBanner)
<section class="relative py-20 bg-carbon text-cream text-center overflow-hidden">
    @if($midBanner->image)
    <img src="{{ asset('storage/' . $midBanner->image) }}" alt="{{ $midBanner->title }}"
         class="absolute inset-0 w-full h-full object-cover opacity-40">
    @endif
    <div class="relative z-10">
        <p class="text-terracota text-xs uppercase tracking-widest mb-4">{{ $midBanner->subtitle }}</p>
        <h2 class="font-serif text-5xl font-light mb-6">{{ $midBanner->title }}</h2>
        @if($midBanner->button_url)
        <a href="{{ $midBanner->button_url }}" class="btn-primary !border-cream !text-cream">
            {{ $midBanner->button_text ?? 'Ver más' }}
        </a>
        @endif
    </div>
</section>
@endif

{{-- PRODUCTOS DESTACADOS --}}
@if($featuredProducts->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-terracota text-xs uppercase tracking-widest mb-2">Selección Editorial</p>
            <h2 class="font-serif text-4xl font-light">Productos Destacados</h2>
        </div>
        <a href="{{ route('product.search') }}?featured=1" class="text-xs uppercase tracking-widest text-stone hover:text-carbon underline">
            Ver todos
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($featuredProducts as $product)
        @include('shop._product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- PRODUCTOS EN OFERTA --}}
@if($saleProducts->count())
<section class="bg-carbon py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-terracota text-xs uppercase tracking-widest mb-2">Precios Irresistibles</p>
                <h2 class="font-serif text-4xl font-light text-cream">En Oferta</h2>
            </div>
            <a href="{{ route('product.search') }}?on_sale=1" class="text-xs uppercase tracking-widest text-stone hover:text-cream underline">
                Ver todas
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($saleProducts as $product)
            @include('shop._product-card', ['product' => $product, 'dark' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TRUST BADGES --}}
<section class="border-t border-stone/20 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <svg class="w-8 h-8 text-terracota mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <h4 class="font-serif text-lg mb-1">Envío Rápido</h4>
                <p class="text-stone text-xs">A todo el Perú en 1-7 días</p>
            </div>
            <div>
                <svg class="w-8 h-8 text-terracota mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <h4 class="font-serif text-lg mb-1">Pago Seguro</h4>
                <p class="text-stone text-xs">Mercado Pago, Yape y Plin</p>
            </div>
            <div>
                <svg class="w-8 h-8 text-terracota mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h4 class="font-serif text-lg mb-1">Contra Entrega</h4>
                <p class="text-stone text-xs">Paga al recibir tu pedido</p>
            </div>
            <div>
                <svg class="w-8 h-8 text-terracota mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <h4 class="font-serif text-lg mb-1">Cambios Fáciles</h4>
                <p class="text-stone text-xs">30 días para cambiar talla o color</p>
            </div>
        </div>
    </div>
</section>

@endsection
