@php
    $dark = $dark ?? false;
    $stock = $product->total_stock;

    // Datos para el modal de vista rápida
    $qvSizes = $product->variants
        ->pluck('size')
        ->filter()
        ->unique('id')
        ->sortBy('sort_order')
        ->map(fn($s) => ['id' => $s->id, 'name' => $s->name])
        ->values()
        ->all();

    $qvColors = $product->variants
        ->pluck('color')
        ->filter()
        ->unique('id')
        ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'hex' => $c->hex_code])
        ->values()
        ->all();

    $qvVariants = $product->variants
        ->map(
            fn($v) => [
                'id' => $v->id,
                'sizeId' => $v->size_id,
                'colorId' => $v->color_id,
                'stock' => $v->stock,
                'price' => (float) $v->final_price,
            ],
        )
        ->values()
        ->all();

    $qvData = json_encode(
        [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->current_price,
            'basePrice' => (float) $product->base_price,
            'isOnSale' => (bool) $product->is_on_sale,
            'discountPct' => $product->discount_percentage ?? 0,
            'image' => $product->primary_image ? asset('storage/' . $product->primary_image) : null,
            'slug' => $product->slug,
            'sizes' => $qvSizes,
            'colors' => $qvColors,
            'variants' => $qvVariants,
        ],
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP,
    );
@endphp

<article class="group relative {{ $dark ? 'bg-carbon/50' : '' }}">

    {{-- Imagen --}}
    <div class="relative bg-stone/10 aspect-[3/4] overflow-hidden">
        @if ($product->primary_image)
            <img src="{{ asset('storage/' . $product->primary_image) }}" alt="{{ $product->name }}" loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="flex justify-center items-center bg-stone/20 w-full h-full">
                <svg class="w-12 h-12 text-stone/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="top-2 left-2 absolute space-y-1">
            @if ($product->is_on_sale)
                <span class="block bg-terracota px-2 py-0.5 text-[10px] text-white uppercase tracking-wider">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
            @if ($product->is_featured)
                <span class="block bg-carbon px-2 py-0.5 text-[10px] text-cream uppercase tracking-wider">
                    Destacado
                </span>
            @endif
            @if ($stock > 0 && $stock <= 5)
                <span class="block bg-urgency px-2 py-0.5 text-[10px] text-white uppercase tracking-wider">
                    ¡Solo {{ $stock }} left!
                </span>
            @endif
        </div>

        {{-- Acciones al hover --}}
        <div
            class="right-0 bottom-0 left-0 absolute flex flex-col transition-transform translate-y-full group-hover:translate-y-0 duration-300">
            {{-- Vista rápida --}}
            <button type="button" data-qv="{{ $qvData }}"
                onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('quick-view', {detail: JSON.parse(this.dataset.qv)}))"
                class="bg-cream/95 hover:bg-cream py-2.5 border-stone/20 border-b w-full text-[10px] text-carbon uppercase tracking-widest transition-colors">
                Vista rápida
            </button>
            {{-- Agregar directo --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit"
                    class="bg-carbon hover:bg-terracota py-3 w-full text-[10px] text-cream uppercase tracking-widest transition-colors">
                    Agregar al carrito
                </button>
            </form>
        </div>
    </div>

    {{-- Info --}}
    <div class="mt-3">
        @if ($product->brand)
            <p class="mb-1 text-[10px] text-stone uppercase tracking-widest">{{ $product->brand->name }}</p>
        @endif
        <h3 class="font-sans font-light text-sm leading-snug">
            <a href="{{ route('product.show', $product->slug) }}"
                class="hover:text-terracota transition-colors {{ $dark ? 'text-cream' : 'text-carbon' }}">
                {{ $product->name }}
            </a>
        </h3>
        <div class="flex items-center gap-2 mt-1.5">
            @if ($product->is_on_sale)
                <span class="font-sans text-sm font-medium {{ $dark ? 'text-cream' : 'text-carbon' }}">
                    S/ {{ number_format($product->current_price, 2) }}
                </span>
                <span class="text-stone text-xs line-through">
                    S/ {{ number_format($product->base_price, 2) }}
                </span>
            @else
                <span class="font-sans text-sm font-medium {{ $dark ? 'text-cream' : 'text-carbon' }}">
                    S/ {{ number_format($product->current_price, 2) }}
                </span>
            @endif
        </div>
        {{-- Tallas disponibles --}}
        @if (count($qvSizes))
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($qvSizes as $sz)
                    <span
                        class="px-1.5 py-0.5 border border-stone/30 text-[10px] text-stone uppercase">{{ $sz['name'] }}</span>
                @endforeach
            </div>
        @endif
    </div>

</article>
