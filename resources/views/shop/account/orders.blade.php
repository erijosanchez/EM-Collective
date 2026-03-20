@extends('layouts.shop')
@section('title', 'Mis Pedidos | EM Collective')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('account.index') }}" class="text-stone hover:text-carbon">←</a>
        <h1 class="font-serif text-4xl font-light">Mis Pedidos</h1>
    </div>

    @if($orders->isEmpty())
    <div class="text-center py-20">
        <p class="text-stone mb-6">Aún no tienes pedidos.</p>
        <a href="{{ route('home') }}" class="btn-primary">Explorar productos</a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($orders as $order)
        <a href="{{ route('account.orders.show', $order) }}"
           class="block p-5 bg-white border border-stone/10 hover:border-stone/40 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-medium text-sm">{{ $order->order_number }}</p>
                    <p class="text-stone text-xs mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-xs text-stone mt-1">{{ $order->items->count() }} producto(s) · {{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-medium">S/ {{ number_format($order->total, 2) }}</p>
                    <span class="inline-block mt-2 text-xs px-2 py-1 uppercase tracking-wider
                        @if($order->status === 'delivered') bg-green-50 text-green-700
                        @elseif($order->status === 'shipped') bg-purple-50 text-purple-700
                        @elseif($order->status === 'processing') bg-blue-50 text-blue-700
                        @elseif($order->status === 'cancelled') bg-red-50 text-red-700
                        @else bg-yellow-50 text-yellow-700
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $orders->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
