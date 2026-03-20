@php $dark = $dark ?? false; @endphp
<article class="group relative {{ $dark ? 'bg-carbon/50' : '' }}">
    {{-- Imagen --}}
    <div class="relative img-hover overflow-hidden aspect-[3/4] bg-stone/10">
        @if($product->primary_image)
        <img src="{{ asset('storage/' . $product->primary_image) }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
        <div class="w-full h-full bg-stone/20 flex items-center justify-center">
            <svg class="w-12 h-12 text-stone/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-2 left-2 space-y-1">
            @if($product->is_on_sale)
            <span class="block bg-terracota text-white text-[10px] px-2 py-0.5 uppercase tracking-wider">
                -{{ $product->discount_percentage }}%
            </span>
            @endif
            @if($product->is_featured)
            <span class="block bg-carbon text-cream text-[10px] px-2 py-0.5 uppercase tracking-wider">
                Destacado
            </span>
            @endif
        </div>

        {{-- Quick add --}}
        <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-full bg-carbon text-cream py-3 text-xs uppercase tracking-widest hover:bg-terracota transition-colors">
                    Agregar al carrito
                </button>
            </form>
        </div>
    </div>

    {{-- Info --}}
    <div class="mt-3">
        @if($product->brand)
        <p class="text-stone text-[10px] uppercase tracking-widest mb-1">{{ $product->brand->name }}</p>
        @endif
        <h3 class="text-sm font-sans font-light leading-snug">
            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-terracota transition-colors {{ $dark ? 'text-cream' : 'text-carbon' }}">
                {{ $product->name }}
            </a>
        </h3>
        <div class="flex items-center gap-2 mt-1.5">
            @if($product->is_on_sale)
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
    </div>
</article>
