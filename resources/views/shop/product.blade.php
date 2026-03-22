@extends('layouts.shop')

@section('title', $product->meta_title ?? $product->name . ' | EM Collective')
@section('description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))
@section('og_type', 'product')
@section('og_title', $product->name . ' | EM Collective')
@section('og_description', Str::limit(strip_tags($product->description ?? ''), 160))
@section('og_image', $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('img/og-default.jpg'))

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ addslashes($product->name) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($product->description ?? ''), 300)) }}",
  "sku": "{{ $product->sku }}",
  "brand": { "@type": "Brand", "name": "{{ $product->brand?->name ?? 'EM Collective' }}" },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('product.show', $product->slug) }}",
    "priceCurrency": "PEN",
    "price": "{{ $product->current_price }}",
    "availability": "{{ $product->total_stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
  @if($product->images->first())
  ,"image": "{{ asset('storage/' . $product->images->first()->path) }}"
  @endif
  @if($product->approvedReviews->count())
  ,"aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ number_format($product->average_rating, 1) }}",
    "reviewCount": "{{ $product->approvedReviews->count() }}"
  }
  @endif
}
</script>
@endsection

@php use Illuminate\Support\Str; @endphp

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-stone text-xs uppercase tracking-widest mb-8">
        <a href="{{ route('home') }}" class="hover:text-carbon">Inicio</a>
        @if($product->category)
        <span class="mx-2">/</span>
        <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-carbon">{{ $product->category->name }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-carbon">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">

        {{-- Galería --}}
        <div x-data="{ active: 0 }" class="flex flex-col-reverse sm:flex-row gap-4">
            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
            <div class="flex sm:flex-col gap-2 overflow-x-auto sm:overflow-y-auto sm:w-20 flex-shrink-0">
                @foreach($product->images as $i => $image)
                <button @click="active = {{ $i }}"
                        class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 overflow-hidden border-2 transition-colors"
                        :class="active === {{ $i }} ? 'border-carbon' : 'border-transparent'">
                    <img src="{{ asset('storage/' . $image->path) }}" alt=""
                         class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif

            {{-- Imagen principal --}}
            <div class="flex-1 relative overflow-hidden aspect-[3/4] bg-stone/10">
                @foreach($product->images as $i => $image)
                <img x-show="active === {{ $i }}"
                     src="{{ asset('storage/' . $image->path) }}"
                     alt="{{ $product->name }}"
                     class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                @endforeach
                @if($product->images->isEmpty())
                <div class="w-full h-full bg-stone/20 flex items-center justify-center">
                    <svg class="w-24 h-24 text-stone/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif

                {{-- Badge oferta --}}
                @if($product->is_on_sale)
                <span class="absolute top-4 left-4 bg-terracota text-white text-xs px-3 py-1 uppercase tracking-wider">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif
            </div>
        </div>

        {{-- Info Producto --}}
        <div x-data="{
            selectedColor: null,
            selectedSize: null,
            quantity: 1,
            variants: {{ $product->variants->map(fn($v) => [
                'id'       => $v->id,
                'size_id'  => $v->size_id,
                'color_id' => $v->color_id,
                'stock'    => $v->stock,
                'price'    => $v->final_price,
            ])->toJson() }},
            get currentVariant() {
                if (!this.selectedSize && !this.selectedColor) return null;
                return this.variants.find(v =>
                    (!this.selectedSize  || v.size_id  == this.selectedSize) &&
                    (!this.selectedColor || v.color_id == this.selectedColor)
                ) || null;
            },
            get currentPrice() {
                return this.currentVariant ? this.currentVariant.price : {{ $product->current_price }};
            },
            get stock() {
                return this.currentVariant ? this.currentVariant.stock : {{ $product->total_stock }};
            },
            get canAdd() {
                return this.stock > 0;
            },
            sizeInStock(sizeId) {
                return this.variants.some(v => v.size_id == sizeId && v.stock > 0 &&
                    (!this.selectedColor || v.color_id == this.selectedColor));
            },
            colorInStock(colorId) {
                return this.variants.some(v => v.color_id == colorId && v.stock > 0 &&
                    (!this.selectedSize || v.size_id == this.selectedSize));
            }
        }">

            {{-- Brand --}}
            @if($product->brand)
            <p class="text-terracota text-xs uppercase tracking-widest mb-2">{{ $product->brand->name }}</p>
            @endif

            <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-light leading-tight mb-4">{{ $product->name }}</h1>

            {{-- Precio --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="font-sans text-2xl font-medium" x-text="'S/ ' + currentPrice.toFixed(2)">
                    S/ {{ number_format($product->current_price, 2) }}
                </span>
                @if($product->is_on_sale)
                <span class="text-stone line-through text-sm">S/ {{ number_format($product->base_price, 2) }}</span>
                <span class="bg-terracota/10 text-terracota text-xs px-2 py-0.5">-{{ $product->discount_percentage }}%</span>
                @endif
            </div>

            {{-- Reseñas --}}
            @if($product->approvedReviews->count())
            <div class="flex items-center gap-2 mb-6">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-terracota' : 'text-stone/30' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <span class="text-stone text-xs">({{ $product->approvedReviews->count() }} reseñas)</span>
            </div>
            @endif

            {{-- Colores --}}
            @php $colors = $product->variants->pluck('color')->filter()->unique('id'); @endphp
            @if($colors->count())
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase tracking-widest">Color</span>
                    <span class="text-stone text-xs" x-show="selectedColor">
                        {{ $colors->first()?->name }}
                    </span>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @foreach($colors as $color)
                    <button
                        @click="selectedColor = {{ $color->id }}"
                        :class="{
                            'ring-2 ring-offset-1 ring-carbon': selectedColor === {{ $color->id }},
                            'opacity-40 cursor-not-allowed': !colorInStock({{ $color->id }})
                        }"
                        title="{{ $color->name }}"
                        class="w-10 h-10 sm:w-8 sm:h-8 rounded-full border border-stone/30 transition-all"
                        style="background: {{ $color->hex_code }}">
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tallas --}}
            @php $sizes = $product->variants->pluck('size')->filter()->unique('id')->sortBy('sort_order'); @endphp
            @if($sizes->count())
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase tracking-widest">Talla</span>
                    <a href="#size-guide" class="text-stone text-xs underline">Guía de tallas</a>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @foreach($sizes as $size)
                    <button
                        @click="selectedSize = {{ $size->id }}"
                        :class="{
                            'bg-carbon text-cream border-carbon': selectedSize === {{ $size->id }},
                            'border-stone/30 text-stone line-through cursor-not-allowed': !sizeInStock({{ $size->id }}),
                            'border-stone/30 text-carbon hover:border-carbon': sizeInStock({{ $size->id }}) && selectedSize !== {{ $size->id }}
                        }"
                        class="px-3 py-3 sm:py-2 border text-xs uppercase tracking-wider min-w-[3rem] transition-all">
                        {{ $size->name }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Stock indicator --}}
            <div class="mb-6">
                <p class="text-xs" :class="stock > 5 ? 'text-stone' : 'text-terracota'">
                    <span x-show="stock > 10">✓ Disponible en stock</span>
                    <span x-show="stock > 0 && stock <= 10">⚡ Solo <span x-text="stock"></span> disponibles</span>
                    <span x-show="stock === 0">✕ Sin stock disponible</span>
                </p>
            </div>

            {{-- Cantidad + Carrito --}}
            <div class="flex gap-3 mb-6">
                {{-- Cantidad --}}
                <div class="flex border border-stone/30">
                    <button @click="if(quantity > 1) quantity--" class="px-3 py-3 text-stone hover:text-carbon transition-colors">−</button>
                    <span x-text="quantity" class="px-4 py-3 text-sm min-w-[3rem] text-center border-x border-stone/30"></span>
                    <button @click="if(quantity < stock) quantity++" class="px-3 py-3 text-stone hover:text-carbon transition-colors">+</button>
                </div>

                {{-- Agregar al carrito --}}
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" :value="quantity">
                    <input type="hidden" name="variant_id" :value="currentVariant?.id">
                    <button type="submit" :disabled="!canAdd"
                            class="w-full btn-primary py-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="canAdd">Agregar al carrito</span>
                        <span x-show="!canAdd">Sin stock</span>
                    </button>
                </form>
            </div>

            {{-- Wishlist --}}
            @auth
            <form action="{{ route('account.wishlist.toggle', $product->id) }}" method="POST" class="mb-8">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-stone hover:text-terracota transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Guardar en wishlist
                </button>
            </form>
            @endauth

            {{-- Accordions --}}
            <div class="border-t border-stone/20 space-y-0">
                {{-- Descripción --}}
                <div x-data="{ open: true }" class="border-b border-stone/20">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs uppercase tracking-widest">
                        Descripción
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pb-4 text-stone text-sm leading-relaxed">
                        {{ $product->description }}
                    </div>
                </div>

                {{-- Detalles / Especificaciones --}}
                @if($product->details)
                <div x-data="{ open: false }" class="border-b border-stone/20">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs uppercase tracking-widest">
                        Especificaciones
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pb-4 text-stone text-sm prose prose-sm max-w-none">
                        {!! $product->details !!}
                    </div>
                </div>
                @endif

                {{-- Guía de tallas --}}
                <div x-data="{ open: false }" id="size-guide" class="border-b border-stone/20">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs uppercase tracking-widest">
                        Guía de Tallas
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pb-4 text-stone text-sm">
                        <div class="overflow-x-auto">
                        <table class="w-full text-xs min-w-[280px]">
                            <thead>
                                <tr class="border-b border-stone/20">
                                    <th class="py-2 text-left uppercase tracking-wider">Talla</th>
                                    <th class="py-2 text-left uppercase tracking-wider">Pecho</th>
                                    <th class="py-2 text-left uppercase tracking-wider">Cintura</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone/10">
                                <tr><td class="py-1.5">XS</td><td>84-87 cm</td><td>65-68 cm</td></tr>
                                <tr><td class="py-1.5">S</td><td>88-91 cm</td><td>69-72 cm</td></tr>
                                <tr><td class="py-1.5">M</td><td>92-95 cm</td><td>73-76 cm</td></tr>
                                <tr><td class="py-1.5">L</td><td>96-99 cm</td><td>77-80 cm</td></tr>
                                <tr><td class="py-1.5">XL</td><td>100-103 cm</td><td>81-84 cm</td></tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reseñas --}}
    @if($product->approvedReviews->count())
    <div class="mt-16 pt-12 border-t border-stone/20">
        <h2 class="font-serif text-3xl font-light mb-8">Reseñas de clientes</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($product->approvedReviews->take(6) as $review)
            <div class="bg-white p-6 border border-stone/20">
                <div class="flex mb-2">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-terracota' : 'text-stone/20' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                @if($review->title)
                <h4 class="font-sans font-medium text-sm mb-1">{{ $review->title }}</h4>
                @endif
                <p class="text-stone text-sm leading-relaxed">{{ $review->body }}</p>
                <p class="text-stone text-xs mt-3">{{ $review->user?->name }} · {{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Relacionados --}}
    @if($relatedProducts->count())
    <div class="mt-16 pt-12 border-t border-stone/20">
        <h2 class="font-serif text-3xl font-light mb-8">También te puede gustar</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            @foreach($relatedProducts as $related)
            @include('shop._product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
