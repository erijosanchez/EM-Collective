@extends('layouts.shop')
@section('title', 'Carrito de Compras | EM Collective')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="font-serif text-2xl sm:text-4xl font-light mb-6 sm:mb-8">Carrito de compras</h1>

    @if($cart->is_empty)
    {{-- Carrito vacío --}}
    <div class="text-center py-24">
        <svg class="w-16 h-16 text-stone/30 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <h2 class="font-serif text-2xl mb-3">Tu carrito está vacío</h2>
        <p class="text-stone mb-8">Explora nuestra colección y encuentra algo que te encante.</p>
        <div class="flex gap-4 justify-center flex-wrap">
            @foreach($navCategories ?? [] as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="btn-outline">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
    @else

    <div class="grid lg:grid-cols-3 gap-6 lg:gap-10">
        {{-- Items --}}
        <div class="lg:col-span-2 space-y-4" id="cart-items">
            @foreach($cart->items as $item)
            <div class="flex gap-3 sm:gap-4 p-3 sm:p-4 bg-white border border-stone/10" id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}">
                {{-- Imagen --}}
                <a href="{{ route('product.show', $item->product->slug) }}" class="flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 bg-stone/10 overflow-hidden">
                    @if($item->product->primary_image)
                    <img src="{{ asset('storage/' . $item->product->primary_image) }}"
                         alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @endif
                </a>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between gap-2">
                        <div>
                            <p class="text-stone text-xs uppercase tracking-wider mb-1">{{ $item->product->brand?->name }}</p>
                            <h3 class="font-sans text-sm font-light leading-snug">
                                <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-terracota">
                                    {{ $item->product->name }}
                                </a>
                            </h3>
                            @if($item->variant)
                            <p class="text-stone text-xs mt-1">{{ $item->variant->label }}</p>
                            @endif
                        </div>
                        <button onclick="removeItem({{ $item->id }})" class="text-stone hover:text-terracota transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        {{-- Cantidad --}}
                        <div class="flex items-center border border-stone/30">
                            <button onclick="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})" class="px-3 py-2 text-stone hover:text-carbon min-w-[2.5rem] text-center">−</button>
                            <span class="px-3 py-2 text-sm border-x border-stone/30 min-w-[2.5rem] text-center">{{ $item->quantity }}</span>
                            <button onclick="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})" class="px-3 py-2 text-stone hover:text-carbon min-w-[2.5rem] text-center">+</button>
                        </div>

                        {{-- Precio --}}
                        <div class="text-right">
                            <p class="font-sans text-sm font-medium">S/ {{ number_format($item->line_total, 2) }}</p>
                            @if($item->quantity > 1)
                            <p class="text-stone text-xs">S/ {{ number_format($item->unit_price, 2) }} c/u</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Resumen --}}
        <div>
            <div class="bg-white border border-stone/10 p-4 sm:p-6 lg:sticky lg:top-24">
                <h2 class="font-serif text-2xl font-light mb-6">Resumen del pedido</h2>

                {{-- Envío gratis progress --}}
                @if($summary['missing_for_free'] > 0)
                <div class="mb-6">
                    <p class="text-xs text-stone mb-2">
                        Agrega <strong class="text-carbon">S/ {{ number_format($summary['missing_for_free'], 2) }}</strong> más para envío gratis
                    </p>
                    <div class="h-1 bg-stone/20 rounded-full overflow-hidden">
                        @php $pct = min(100, (($summary['subtotal'] - $summary['discount']) / $summary['free_threshold']) * 100); @endphp
                        <div class="h-full bg-terracota rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @else
                <p class="text-xs text-terracota mb-6">✓ ¡Tienes envío gratis!</p>
                @endif

                {{-- Totales --}}
                <div class="space-y-3 text-sm border-t border-stone/20 pt-4" id="cart-summary">
                    <div class="flex justify-between">
                        <span class="text-stone">Subtotal</span>
                        <span>S/ {{ number_format($summary['subtotal'], 2) }}</span>
                    </div>
                    @if($summary['discount'] > 0)
                    <div class="flex justify-between text-terracota">
                        <span>Descuento ({{ $cart->coupon?->code }})</span>
                        <span>-S/ {{ number_format($summary['discount'], 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-stone">Envío</span>
                        <span>{{ $summary['shipping'] > 0 ? 'S/ ' . number_format($summary['shipping'], 2) : 'Gratis' }}</span>
                    </div>
                    <div class="flex justify-between font-medium text-base pt-3 border-t border-stone/20">
                        <span>Total</span>
                        <span>S/ {{ number_format($summary['total'], 2) }}</span>
                    </div>
                </div>

                {{-- Cupón --}}
                @if($cart->hasCoupon())
                <div class="mt-4 flex items-center justify-between bg-stone/10 px-3 py-2">
                    <span class="text-xs font-medium">{{ $cart->coupon->code }}</span>
                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-stone hover:text-terracota text-xs">Quitar</button>
                    </form>
                </div>
                @else
                <form action="{{ route('cart.coupon') }}" method="POST" class="mt-4 flex gap-0">
                    @csrf
                    <input type="text" name="code" placeholder="Código de cupón"
                           class="flex-1 border border-stone/30 px-3 py-2 text-xs focus:outline-none focus:border-carbon uppercase">
                    <button type="submit" class="btn-primary !py-2 text-xs">Aplicar</button>
                </form>
                @if($errors->has('code'))
                <p class="text-terracota text-xs mt-1">{{ $errors->first('code') }}</p>
                @endif
                @endif

                <a href="{{ route('checkout.index') }}" class="btn-primary w-full text-center mt-6 block">
                    Proceder al Checkout
                </a>
                <a href="{{ route('home') }}" class="block text-center text-stone text-xs mt-3 hover:text-carbon">
                    ← Seguir comprando
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function updateQty(itemId, qty) {
    if (qty < 0) return;
    const res = await fetch(`/carrito/${itemId}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ quantity: qty })
    });
    const data = await res.json();
    if (data.success) {
        if (data.removed) {
            document.getElementById(`cart-item-${itemId}`)?.remove();
        }
        window.location.reload();
    }
}

async function removeItem(itemId) {
    const res = await fetch(`/carrito/${itemId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById(`cart-item-${itemId}`)?.remove();
        window.location.reload();
    }
}
</script>
@endsection
