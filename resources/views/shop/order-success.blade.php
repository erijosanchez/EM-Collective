@extends('layouts.shop')
@section('title', 'Pedido Confirmado | EM Collective')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-20 text-center">
    <div class="w-16 h-16 bg-terracota/10 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="font-serif text-4xl font-light mb-3">¡Pedido confirmado!</h1>
    <p class="text-stone mb-2">Gracias por tu compra, <strong>{{ $order->customer_name }}</strong></p>
    <p class="text-terracota font-medium mb-8">{{ $order->order_number }}</p>

    <div class="bg-white border border-stone/10 p-6 text-left mb-8">
        <h3 class="font-serif text-lg mb-4">Detalles del pedido</h3>
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm">
                <span class="text-stone">{{ $item->product_name }} × {{ $item->quantity }}</span>
                <span>S/ {{ number_format($item->subtotal, 2) }}</span>
            </div>
            @endforeach
            <div class="border-t border-stone/20 pt-3 space-y-1 text-sm">
                <div class="flex justify-between text-stone">
                    <span>Envío</span>
                    <span>{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : 'Gratis' }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-terracota">
                    <span>Descuento</span>
                    <span>-S/ {{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-medium text-base">
                    <span>Total</span>
                    <span>S/ {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="border-t border-stone/20 mt-4 pt-4 text-sm text-stone space-y-1">
            <p><strong class="text-carbon">Envío a:</strong> {{ $order->shipping_address }}, {{ $order->shipping_district }}, {{ $order->shipping_province }}</p>
            <p><strong class="text-carbon">Pago:</strong> {{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</p>
        </div>
    </div>

    <p class="text-stone text-sm mb-8">
        Te enviamos la confirmación a <strong>{{ $order->customer_email }}</strong>.<br>
        Te notificaremos cuando tu pedido esté en camino.
    </p>

    <div class="flex gap-4 justify-center flex-wrap">
        @auth
        <a href="{{ route('account.orders') }}" class="btn-primary">Ver mis pedidos</a>
        @endauth
        <a href="{{ route('home') }}" class="btn-outline">Seguir comprando</a>
    </div>
</div>
@endsection
