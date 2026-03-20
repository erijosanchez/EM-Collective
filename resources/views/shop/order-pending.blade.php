@extends('layouts.shop')
@section('title', 'Pago Pendiente | EM Collective')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-20 text-center">
    <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="font-serif text-4xl font-light mb-3">Pago pendiente</h1>
    <p class="text-stone mb-2">Tu pedido <strong>{{ $order->order_number }}</strong> está en espera de confirmación de pago.</p>
    <p class="text-stone text-sm mb-10">
        Una vez que Mercado Pago confirme el pago, recibirás un correo de confirmación a <strong>{{ $order->customer_email }}</strong>.
        Esto puede tardar unos minutos.
    </p>

    <div class="flex gap-4 justify-center flex-wrap">
        @auth
        <a href="{{ route('account.orders') }}" class="btn-primary">Ver mis pedidos</a>
        @endauth
        <a href="{{ route('home') }}" class="btn-outline">Volver al inicio</a>
    </div>
</div>
@endsection
