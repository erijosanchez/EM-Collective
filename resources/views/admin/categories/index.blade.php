@extends('admin.layouts.admin')
@section('title', 'Categorías')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Categorías</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn-admin btn-admin-primary">+ Nueva categoría</a>
</div>

<div class="card overflow-hidden">
    @foreach($categories as $parent)
    <div class="border-b border-stone/10">
        <div class="flex items-center justify-between px-5 py-3 hover:bg-panel">
            <div class="flex items-center gap-3">
                @if($parent->image)
                <img src="{{ asset('storage/' . $parent->image) }}" class="w-8 h-8 object-cover rounded-sm">
                @else
                <div class="w-8 h-8 bg-stone/20 rounded-sm"></div>
                @endif
                <div>
                    <p class="font-medium text-sm">{{ $parent->name }}</p>
                    <p class="text-stone text-xs">{{ $parent->children->count() }} subcategorías</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs {{ $parent->is_active ? 'text-green-400' : 'text-stone' }}">
                    {{ $parent->is_active ? 'Activa' : 'Inactiva' }}
                </span>
                <a href="{{ route('admin.categories.edit', $parent) }}" class="text-stone hover:text-cream text-xs uppercase tracking-widest">Editar</a>
                <form action="{{ route('admin.categories.destroy', $parent) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar categoría?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-stone hover:text-red-400 text-xs">Eliminar</button>
                </form>
            </div>
        </div>

        @if($parent->children->count())
        @foreach($parent->children as $child)
        <div class="flex items-center justify-between px-5 py-2 hover:bg-panel border-t border-stone/5 pl-12">
            <p class="text-stone text-sm">— {{ $child->name }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.categories.edit', $child) }}" class="text-stone hover:text-cream text-xs uppercase tracking-widest">Editar</a>
                <form action="{{ route('admin.categories.destroy', $child) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar subcategoría?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-stone hover:text-red-400 text-xs">Eliminar</button>
                </form>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @endforeach
</div>
@endsection
