@extends('layouts.shop')
@section('title', 'Pedido ' . $order->order_number . ' | EM Collective')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('account.orders') }}" class="text-stone hover:text-carbon">←</a>
        <div>
            <h1 class="font-serif text-3xl font-light">{{ $order->order_number }}</h1>
            <p class="text-stone text-sm">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="ml-auto text-xs px-3 py-1.5 uppercase tracking-wider
            @if($order->status === 'delivered') bg-green-50 text-green-700
            @elseif($order->status === 'shipped') bg-purple-50 text-purple-700
            @elseif($order->status === 'cancelled') bg-red-50 text-red-700
            @else bg-yellow-50 text-yellow-700
            @endif">
            {{ $order->status_label }}
        </span>
    </div>

    {{-- Items --}}
    <div class="bg-white border border-stone/10 p-6 mb-6">
        <h3 class="font-serif text-lg mb-4">Productos</h3>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex gap-4">
                @if($item->product_image)
                <div class="w-16 h-20 bg-stone/10 flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('storage/' . $item->product_image) }}" class="w-full h-full object-cover">
                </div>
                @endif
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ $item->product_name }}</p>
                    @if($item->variant_label)
                    <p class="text-stone text-xs">{{ $item->variant_label }}</p>
                    @endif
                    <div class="flex justify-between mt-1 text-sm">
                        <span class="text-stone">× {{ $item->quantity }}</span>
                        <span>S/ {{ number_format($item->subtotal, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="border-t border-stone/20 mt-6 pt-4 space-y-2 text-sm">
            <div class="flex justify-between text-stone"><span>Subtotal</span><span>S/ {{ number_format($order->subtotal, 2) }}</span></div>
            @if($order->discount_amount > 0)
            <div class="flex justify-between text-terracota"><span>Descuento</span><span>-S/ {{ number_format($order->discount_amount, 2) }}</span></div>
            @endif
            <div class="flex justify-between text-stone"><span>Envío</span><span>{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : 'Gratis' }}</span></div>
            <div class="flex justify-between font-medium text-base border-t border-stone/20 pt-2">
                <span>Total</span><span>S/ {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
        {{-- Envío --}}
        <div class="bg-white border border-stone/10 p-6">
            <h3 class="font-serif text-lg mb-3">Dirección de envío</h3>
            <p class="text-sm">{{ $order->customer_name }}</p>
            <p class="text-stone text-sm">{{ $order->shipping_address }}</p>
            <p class="text-stone text-sm">{{ $order->shipping_district }}, {{ $order->shipping_province }}</p>
            <p class="text-stone text-sm">{{ $order->shipping_department }}</p>
            @if($order->tracking_code)
            <p class="mt-3 text-sm"><strong>Tracking:</strong> {{ $order->tracking_code }}</p>
            @endif
        </div>

        {{-- Pago --}}
        <div class="bg-white border border-stone/10 p-6">
            <h3 class="font-serif text-lg mb-3">Pago</h3>
            <p class="text-sm">{{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</p>
            <span class="inline-block mt-2 text-xs px-2 py-1 uppercase tracking-wider
                {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                {{ $order->payment_status === 'paid' ? 'Pagado' : ($order->payment_status === 'pending' ? 'Pendiente' : 'Fallido') }}
            </span>
            @if($order->payment_reference)
            <p class="text-stone text-xs mt-2">Ref: {{ $order->payment_reference }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
