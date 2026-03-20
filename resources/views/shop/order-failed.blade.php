@extends('layouts.shop')
@section('title', 'Pago Fallido | EM Collective')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-20 text-center">
    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </div>
    <h1 class="font-serif text-4xl font-light mb-3">Pago no procesado</h1>
    <p class="text-stone mb-2">El pago del pedido <strong>{{ $order->order_number }}</strong> no pudo completarse.</p>
    <p class="text-stone text-sm mb-10">Esto puede deberse a fondos insuficientes, datos incorrectos o un error temporal. Puedes intentarlo de nuevo.</p>

    <div class="flex gap-4 justify-center flex-wrap">
        <a href="{{ route('checkout.index') }}" class="btn-primary">Intentar de nuevo</a>
        <a href="{{ route('home') }}" class="btn-outline">Volver al inicio</a>
    </div>
</div>
@endsection
