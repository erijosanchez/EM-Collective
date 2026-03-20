@extends('layouts.shop')
@section('title', $q ? "Búsqueda: {$q} | EM Collective" : 'Búsqueda | EM Collective')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <div class="mb-8">
        <h1 class="font-serif text-4xl font-light">
            @if($q) Resultados para "<em>{{ $q }}</em>" @else Todos los productos @endif
        </h1>
        <p class="text-stone mt-2">{{ $products->total() }} productos encontrados</p>
    </div>

    {{-- Filtros rápidos --}}
    <form method="GET" action="{{ route('product.search') }}" class="flex flex-wrap gap-3 mb-8 items-center">
        <input type="hidden" name="q" value="{{ $q }}">

        <select name="category_id" onchange="this.form.submit()"
                class="border border-stone/30 px-3 py-2 text-xs bg-cream focus:outline-none">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <select name="sort" onchange="this.form.submit()"
                class="border border-stone/30 px-3 py-2 text-xs bg-cream focus:outline-none">
            <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>Relevancia</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más recientes</option>
        </select>

        <label class="flex items-center gap-2 text-sm text-stone cursor-pointer">
            <input type="checkbox" name="on_sale" value="1" class="accent-terracota"
                   {{ request('on_sale') ? 'checked' : '' }} onchange="this.form.submit()">
            Solo en oferta
        </label>
    </form>

    @if($products->isEmpty())
    <div class="text-center py-24">
        <svg class="w-16 h-16 text-stone/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h2 class="font-serif text-2xl mb-2">Sin resultados</h2>
        <p class="text-stone text-sm mb-6">No encontramos productos para "{{ $q }}".</p>
        <a href="{{ route('home') }}" class="btn-primary">Volver al inicio</a>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($products as $product)
        @include('shop._product-card', ['product' => $product])
        @endforeach
    </div>
    <div class="mt-12">
        {{ $products->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
