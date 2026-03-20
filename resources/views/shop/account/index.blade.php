@extends('layouts.shop')
@section('title', 'Mi Cuenta | EM Collective')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-terracota text-xs uppercase tracking-widest mb-1">Bienvenida de vuelta</p>
            <h1 class="font-serif text-4xl font-light">{{ $user->name }}</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-stone text-xs uppercase tracking-widest hover:text-carbon">Cerrar sesión</button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
        <div class="bg-white border border-stone/10 p-6">
            <p class="text-3xl font-serif font-light">{{ $totalOrders }}</p>
            <p class="text-stone text-xs uppercase tracking-widest mt-1">Pedidos</p>
        </div>
        <div class="bg-white border border-stone/10 p-6">
            <p class="text-3xl font-serif font-light">S/ {{ number_format($totalSpent, 0) }}</p>
            <p class="text-stone text-xs uppercase tracking-widest mt-1">Gastado</p>
        </div>
        <div class="bg-white border border-stone/10 p-6">
            <p class="text-3xl font-serif font-light">{{ $wishlistCount }}</p>
            <p class="text-stone text-xs uppercase tracking-widest mt-1">Guardados</p>
        </div>
        <div class="bg-terracota/10 border border-terracota/20 p-6">
            <p class="text-3xl font-serif font-light text-terracota">VIP</p>
            <p class="text-stone text-xs uppercase tracking-widest mt-1">Estado</p>
        </div>
    </div>

    {{-- Quick links --}}
    <div class="grid sm:grid-cols-4 gap-3 mb-12">
        @foreach([
            ['route' => route('account.orders'), 'label' => 'Mis Pedidos', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['route' => route('account.wishlist'), 'label' => 'Wishlist', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['route' => route('account.addresses'), 'label' => 'Direcciones', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
            ['route' => route('account.profile'), 'label' => 'Mi Perfil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ] as $link)
        <a href="{{ $link['route'] }}" class="flex flex-col items-center gap-2 p-5 bg-white border border-stone/10 hover:border-carbon transition-colors text-center">
            <svg class="w-6 h-6 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $link['icon'] }}"/>
            </svg>
            <span class="text-xs uppercase tracking-widest">{{ $link['label'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- Últimos pedidos --}}
    @if($recentOrders->count())
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-serif text-2xl font-light">Últimos pedidos</h2>
            <a href="{{ route('account.orders') }}" class="text-xs uppercase tracking-widest text-stone hover:text-carbon underline">Ver todos</a>
        </div>
        <div class="space-y-2">
            @foreach($recentOrders as $order)
            <a href="{{ route('account.orders.show', $order) }}" class="flex items-center justify-between p-4 bg-white border border-stone/10 hover:border-stone/40 transition-colors">
                <div>
                    <p class="text-sm font-medium">{{ $order->order_number }}</p>
                    <p class="text-stone text-xs">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} producto(s)</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm">S/ {{ number_format($order->total, 2) }}</span>
                    <span class="text-xs px-2 py-1 uppercase tracking-wider
                        @if($order->status === 'delivered') bg-green-50 text-green-700
                        @elseif($order->status === 'shipped') bg-purple-50 text-purple-700
                        @elseif($order->status === 'cancelled') bg-red-50 text-red-700
                        @else bg-yellow-50 text-yellow-700
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
