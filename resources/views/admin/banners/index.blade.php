@extends('admin.layouts.admin')
@section('title', 'Banners')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn-admin btn-admin-primary">+ Nuevo banner</a>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                <th class="px-5 py-3 text-left w-20">Preview</th>
                <th class="px-5 py-3 text-left">Título</th>
                <th class="px-5 py-3 text-left">Posición</th>
                <th class="px-5 py-3 text-left">Vigencia</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
            <tr class="table-row">
                <td class="px-5 py-3">
                    @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" class="w-16 h-10 object-cover">
                    @else
                    <div class="w-16 h-10 bg-stone/20"></div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium">{{ $banner->title }}</p>
                    @if($banner->subtitle)
                    <p class="text-stone text-xs">{{ $banner->subtitle }}</p>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 bg-stone/20 text-stone uppercase tracking-wider">{{ $banner->position }}</span>
                </td>
                <td class="px-5 py-3 text-stone text-xs">
                    @if($banner->starts_at || $banner->ends_at)
                    {{ $banner->starts_at?->format('d/m') }} — {{ $banner->ends_at?->format('d/m/Y') ?? '∞' }}
                    @else
                    Siempre
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 {{ $banner->is_active ? 'bg-green-900/30 text-green-400' : 'bg-stone/20 text-stone' }}">
                        {{ $banner->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-stone hover:text-cream text-xs">Editar</a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-stone hover:text-red-400 text-xs">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-stone">Sin banners.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
