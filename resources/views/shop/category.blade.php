@extends('layouts.shop')

@section('title', $category->meta_title ?? $category->name . ' | EM Collective')
@section('description', $category->meta_description ?? '')

@section('content')

{{-- Hero --}}
<div class="bg-carbon text-cream py-10 sm:py-16 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">
        <nav class="text-stone text-xs uppercase tracking-widest mb-4 flex flex-wrap gap-1 items-center">
            <a href="{{ route('home') }}" class="hover:text-cream">Inicio</a>
            @if($category->parent)
            <span class="mx-1">/</span>
            <a href="{{ route('category.show', $category->parent->slug) }}" class="hover:text-cream">{{ $category->parent->name }}</a>
            @endif
            <span class="mx-1">/</span>
            <span class="text-cream">{{ $category->name }}</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-light">{{ $category->name }}</h1>
        @if($category->description)
        <p class="text-stone mt-2 max-w-xl text-sm sm:text-base">{{ $category->description }}</p>
        @endif
    </div>
</div>

{{-- Subcategorías tabs --}}
@if($category->children && $category->children->count())
<div class="border-b border-stone/20 bg-cream sticky top-16 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex gap-4 sm:gap-6 overflow-x-auto scrollbar-hide pb-px">
        <a href="{{ route('category.show', $category->slug) }}"
           class="py-3 text-xs uppercase tracking-widest whitespace-nowrap border-b-2 transition-colors {{ !$activeChild ? 'border-carbon text-carbon' : 'border-transparent text-stone hover:text-carbon' }}">
            Todos
        </a>
        @foreach($category->children as $child)
        <a href="{{ route('category.show', $category->slug) }}?sub={{ $child->slug }}"
           class="py-3 text-xs uppercase tracking-widest whitespace-nowrap border-b-2 transition-colors {{ $activeChild?->id === $child->id ? 'border-carbon text-carbon' : 'border-transparent text-stone hover:text-carbon' }}">
            {{ $child->name }}
        </a>
        @endforeach
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8" x-data="{ filtersOpen: false, cols: 3 }">
    <div class="flex gap-6 lg:gap-8">

        {{-- Sidebar Filtros --}}
        <aside class="hidden lg:block w-56 flex-shrink-0">
            <form method="GET" action="{{ route('category.show', $category->slug) }}" id="filters-form">
                @if($activeChild)
                <input type="hidden" name="sub" value="{{ $activeChild->slug }}">
                @endif

                {{-- Ordenar --}}
                <div class="mb-8">
                    <h3 class="text-xs uppercase tracking-widest mb-4 text-carbon">Ordenar por</h3>
                    @foreach([
                        'relevance'  => 'Relevancia',
                        'price_asc'  => 'Precio: menor a mayor',
                        'price_desc' => 'Precio: mayor a menor',
                        'newest'     => 'Más recientes',
                    ] as $value => $label)
                    <label class="flex items-center gap-2 mb-2 cursor-pointer">
                        <input type="radio" name="sort" value="{{ $value }}"
                               class="accent-terracota"
                               {{ request('sort', 'relevance') === $value ? 'checked' : '' }}
                               onchange="document.getElementById('filters-form').submit()">
                        <span class="text-sm text-stone">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Precio --}}
                <div class="mb-8">
                    <h3 class="text-xs uppercase tracking-widest mb-4 text-carbon">Precio (S/)</h3>
                    <div class="flex gap-2">
                        <input type="number" name="price_min" placeholder="Mín"
                               value="{{ request('price_min') }}"
                               class="w-full border border-stone/40 px-2 py-1.5 text-xs focus:outline-none focus:border-carbon">
                        <input type="number" name="price_max" placeholder="Máx"
                               value="{{ request('price_max') }}"
                               class="w-full border border-stone/40 px-2 py-1.5 text-xs focus:outline-none focus:border-carbon">
                    </div>
                </div>

                {{-- Solo oferta --}}
                <div class="mb-8">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="on_sale" value="1" class="accent-terracota"
                               {{ request('on_sale') ? 'checked' : '' }}
                               onchange="document.getElementById('filters-form').submit()">
                        <span class="text-sm text-stone">Solo en oferta</span>
                    </label>
                </div>

                {{-- Tallas --}}
                @if($sizes->count())
                <div class="mb-8">
                    <h3 class="text-xs uppercase tracking-widest mb-4 text-carbon">Talla</h3>
                    @foreach($sizes as $size)
                    <label class="flex items-center gap-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                               class="accent-terracota"
                               {{ in_array($size->id, request('sizes', [])) ? 'checked' : '' }}
                               onchange="document.getElementById('filters-form').submit()">
                        <span class="text-sm text-stone">{{ $size->name }}</span>
                    </label>
                    @endforeach
                </div>
                @endif

                {{-- Colores --}}
                @if($colors->count())
                <div class="mb-8">
                    <h3 class="text-xs uppercase tracking-widest mb-4 text-carbon">Color</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $color)
                        <label class="cursor-pointer" title="{{ $color->name }}">
                            <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                   class="sr-only peer"
                                   {{ in_array($color->id, request('colors', [])) ? 'checked' : '' }}
                                   onchange="document.getElementById('filters-form').submit()">
                            <span class="block w-7 h-7 rounded-full border-2 peer-checked:border-carbon border-transparent ring-1 ring-stone/30"
                                  style="background: {{ $color->hex_code }}"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="submit" class="btn-primary w-full text-center">Aplicar</button>
            </form>
        </aside>

        {{-- Productos --}}
        <div class="flex-1 min-w-0">
            {{-- Toolbar --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-stone text-sm">{{ $products->total() }} productos</p>
                <div class="flex items-center gap-3">
                    {{-- Mobile filters --}}
                    <button @click="filtersOpen = true" class="lg:hidden flex items-center gap-1.5 text-xs uppercase tracking-widest border border-stone/40 px-3 py-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 12h10M11 20h2"/></svg>
                        Filtros
                    </button>
                    {{-- Grid cols --}}
                    <div class="hidden sm:flex gap-1">
                        <button @click="cols = 2" class="p-1.5 text-stone hover:text-carbon" :class="cols===2 ? 'text-carbon' : ''">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/></svg>
                        </button>
                        <button @click="cols = 3" class="p-1.5 text-stone hover:text-carbon" :class="cols===3 ? 'text-carbon' : ''">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="5" height="8"/><rect x="9.5" y="3" width="5" height="8"/><rect x="17" y="3" width="5" height="8"/><rect x="2" y="13" width="5" height="8"/><rect x="9.5" y="13" width="5" height="8"/><rect x="17" y="13" width="5" height="8"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-24">
                <svg class="w-16 h-16 text-stone/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-serif text-2xl mb-2">Sin resultados</h3>
                <p class="text-stone text-sm mb-6">No encontramos productos con esos filtros.</p>
                <a href="{{ route('category.show', $category->slug) }}" class="btn-primary">Limpiar filtros</a>
            </div>
            @else
            <div :class="cols === 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-3'" class="grid gap-4 sm:gap-6">
                @foreach($products as $product)
                @include('shop._product-card', ['product' => $product])
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-12">
                {{ $products->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Mobile Bottom Sheet Filtros --}}
    <div x-show="filtersOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         style="display:none"
         class="fixed inset-0 z-50 lg:hidden">
        <div class="fixed inset-0 bg-carbon/50" @click="filtersOpen = false"></div>
        <div class="fixed bottom-0 left-0 right-0 bg-cream rounded-t-2xl max-h-[88vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
            {{-- Handle --}}
            <div class="flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-stone/30 rounded-full"></div>
            </div>
            <div class="flex items-center justify-between px-6 py-4 border-b border-stone/20">
                <h3 class="font-serif text-xl">Filtros</h3>
                <button @click="filtersOpen = false" class="p-1 text-stone hover:text-carbon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="GET" action="{{ route('category.show', $category->slug) }}" class="p-6 space-y-6">
                @if($activeChild)
                <input type="hidden" name="sub" value="{{ $activeChild->slug }}">
                @endif

                {{-- Ordenar --}}
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-3 text-carbon">Ordenar por</h4>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['relevance' => 'Relevancia', 'price_asc' => 'Menor precio', 'price_desc' => 'Mayor precio', 'newest' => 'Más recientes'] as $value => $label)
                        <label class="flex items-center gap-2 cursor-pointer py-2 px-3 border border-stone/20 rounded {{ request('sort','relevance') === $value ? 'border-carbon bg-carbon/5' : '' }}">
                            <input type="radio" name="sort" value="{{ $value }}" class="accent-terracota"
                                   {{ request('sort', 'relevance') === $value ? 'checked' : '' }}>
                            <span class="text-sm">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Precio --}}
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-3 text-carbon">Precio (S/)</h4>
                    <div class="flex gap-3">
                        <input type="number" name="price_min" placeholder="Mín" value="{{ request('price_min') }}"
                               class="w-full border border-stone/40 px-3 py-2.5 text-sm focus:outline-none focus:border-carbon">
                        <input type="number" name="price_max" placeholder="Máx" value="{{ request('price_max') }}"
                               class="w-full border border-stone/40 px-3 py-2.5 text-sm focus:outline-none focus:border-carbon">
                    </div>
                </div>

                {{-- Solo oferta --}}
                <label class="flex items-center gap-3 cursor-pointer py-3 px-4 border border-stone/20 rounded {{ request('on_sale') ? 'border-terracota bg-terracota/5' : '' }}">
                    <input type="checkbox" name="on_sale" value="1" class="accent-terracota w-4 h-4"
                           {{ request('on_sale') ? 'checked' : '' }}>
                    <span class="text-sm">Solo productos en oferta</span>
                </label>

                {{-- Tallas --}}
                @if($sizes->count())
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-3 text-carbon">Talla</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="sr-only peer"
                                   {{ in_array($size->id, request('sizes', [])) ? 'checked' : '' }}>
                            <span class="block px-4 py-2 border text-sm uppercase tracking-wider transition-colors peer-checked:bg-carbon peer-checked:text-cream peer-checked:border-carbon border-stone/30 hover:border-carbon">
                                {{ $size->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Colores --}}
                @if($colors->count())
                <div>
                    <h4 class="text-xs uppercase tracking-widest mb-3 text-carbon">Color</h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach($colors as $color)
                        <label class="cursor-pointer" title="{{ $color->name }}">
                            <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="sr-only peer"
                                   {{ in_array($color->id, request('colors', [])) ? 'checked' : '' }}>
                            <span class="block w-9 h-9 rounded-full border-2 peer-checked:border-carbon border-transparent ring-1 ring-stone/30"
                                  style="background: {{ $color->hex_code }}"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1 text-center">Aplicar filtros</button>
                    <a href="{{ route('category.show', $category->slug) }}" class="btn-outline px-4">Limpiar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
