@extends('layouts.shop')
@section('title', 'Mis Direcciones | EM Collective')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12" x-data="{ showForm: {{ $addresses->isEmpty() ? 'true' : 'false' }} }">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('account.index') }}" class="text-stone hover:text-carbon">←</a>
        <h1 class="font-serif text-4xl font-light">Mis Direcciones</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm p-3 mb-6">{{ session('success') }}</div>
    @endif

    {{-- Direcciones existentes --}}
    @if($addresses->count())
    <div class="space-y-3 mb-8">
        @foreach($addresses as $addr)
        <div class="bg-white border border-stone/10 p-5 flex items-start justify-between gap-4">
            <div>
                @if($addr->is_default)
                <span class="text-[10px] uppercase tracking-wider bg-terracota/10 text-terracota px-2 py-0.5 mr-2">Predeterminada</span>
                @endif
                <p class="font-medium text-sm mt-1">{{ $addr->first_name }} {{ $addr->last_name }}</p>
                <p class="text-stone text-xs">{{ $addr->address }}</p>
                <p class="text-stone text-xs">{{ $addr->district }}, {{ $addr->province }}, {{ $addr->department }}</p>
                <p class="text-stone text-xs">{{ $addr->phone }}</p>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                @if(!$addr->is_default)
                <form action="{{ route('account.addresses.default', $addr) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs text-stone hover:text-carbon underline">Predeterminar</button>
                </form>
                @endif
                <form action="{{ route('account.addresses.destroy', $addr) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta dirección?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-terracota hover:text-red-600">Eliminar</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Toggle formulario --}}
    <button @click="showForm = !showForm" class="btn-outline mb-6">
        <span x-show="!showForm">+ Agregar nueva dirección</span>
        <span x-show="showForm">Cancelar</span>
    </button>

    {{-- Formulario --}}
    <div x-show="showForm" x-transition class="bg-white border border-stone/10 p-6">
        <h3 class="font-serif text-xl mb-6">Nueva dirección</h3>
        <form action="{{ route('account.addresses.store') }}" method="POST">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Nombre *</label>
                    <input type="text" name="first_name" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Apellido *</label>
                    <input type="text" name="last_name" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Teléfono *</label>
                    <input type="tel" name="phone" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">DNI</label>
                    <input type="text" name="dni" class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Departamento *</label>
                    <input type="text" name="department" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Provincia *</label>
                    <input type="text" name="province" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Distrito *</label>
                    <input type="text" name="district" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs uppercase tracking-widest mb-2">Dirección *</label>
                    <input type="text" name="address" required class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs uppercase tracking-widest mb-2">Referencia</label>
                    <input type="text" name="reference" class="w-full border border-stone/30 px-3 py-2 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="accent-carbon">
                        <span class="text-sm text-stone">Establecer como predeterminada</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-primary mt-6">Guardar dirección</button>
        </form>
    </div>
</div>
@endsection
