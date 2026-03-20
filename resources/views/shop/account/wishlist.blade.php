@extends('layouts.shop')
@section('title', 'Mi Wishlist | EM Collective')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('account.index') }}" class="text-stone hover:text-carbon">←</a>
        <h1 class="font-serif text-4xl font-light">Mi Wishlist</h1>
    </div>

    @if($products->isEmpty())
    <div class="text-center py-20">
        <svg class="w-12 h-12 text-stone/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <p class="text-stone mb-6">Tu wishlist está vacía.</p>
        <a href="{{ route('home') }}" class="btn-primary">Explorar productos</a>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($products as $product)
        <div class="group relative">
            @include('shop._product-card', ['product' => $product])
            <form action="{{ route('account.wishlist.toggle', $product->id) }}" method="POST"
                  class="absolute top-2 right-2">
                @csrf
                <button type="submit" class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center hover:bg-terracota hover:text-white transition-colors">
                    <svg class="w-4 h-4 text-terracota group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $products->links('vendor.pagination.custom') }}</div>
    @endif
</div>
@endsection
