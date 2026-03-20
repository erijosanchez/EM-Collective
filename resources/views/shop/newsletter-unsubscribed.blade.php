@extends('layouts.shop')
@section('title', 'Desuscripción | EM Collective')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 py-24 text-center">
    <h1 class="font-serif text-4xl font-light mb-4">Has sido desuscrito</h1>
    <p class="text-stone mb-8">Tu correo ha sido eliminado de nuestra lista de newsletter. Ya no recibirás emails de marketing de EM Collective.</p>
    <p class="text-stone text-sm mb-10">Si esto fue un error, puedes volver a suscribirte en cualquier momento desde nuestra tienda.</p>
    <a href="{{ route('home') }}" class="btn-primary">Volver al inicio</a>
</div>
@endsection
