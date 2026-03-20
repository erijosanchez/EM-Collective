@extends('admin.layouts.admin')
@section('title', 'Productos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Productos</h1>
    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin-primary">+ Nuevo producto</a>
</div>

{{-- Filtros --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
           class="form-input !w-48">
    <select name="category_id" class="form-input !w-48">
        <option value="">Todas las categorías</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @foreach($cat->children as $child)
        <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
        @endforeach
        @endforeach
    </select>
    <select name="status" class="form-input !w-36">
        <option value="">Todos</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
    </select>
    <button type="submit" class="btn-admin btn-admin-ghost">Filtrar</button>
    @if(request()->hasAny(['search','category_id','status']))
    <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-ghost">Limpiar</a>
    @endif
</form>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                <th class="px-5 py-3 text-left w-16">Foto</th>
                <th class="px-5 py-3 text-left">Nombre</th>
                <th class="px-5 py-3 text-left">Categoría</th>
                <th class="px-5 py-3 text-left">Precio</th>
                <th class="px-5 py-3 text-left">Stock</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="table-row">
                <td class="px-5 py-3">
                    @if($product->primary_image)
                    <img src="{{ asset('storage/' . $product->primary_image) }}" class="w-10 h-12 object-cover">
                    @else
                    <div class="w-10 h-12 bg-stone/20"></div>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium">{{ $product->name }}</p>
                    @if($product->brand)
                    <p class="text-stone text-xs">{{ $product->brand->name }}</p>
                    @endif
                    @if($product->is_featured)
                    <span class="text-[10px] bg-terracota/20 text-terracota px-1.5 py-0.5">Destacado</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-stone">{{ $product->category?->name }}</td>
                <td class="px-5 py-3">
                    S/ {{ number_format($product->current_price, 2) }}
                    @if($product->is_on_sale)
                    <span class="text-xs line-through text-stone ml-1">{{ number_format($product->base_price, 2) }}</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    @php $stock = $product->variants_count; @endphp
                    <span class="{{ $product->total_stock === 0 ? 'text-red-400' : ($product->total_stock < 5 ? 'text-yellow-400' : 'text-green-400') }}">
                        {{ $product->total_stock }} uds
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 {{ $product->is_active ? 'bg-green-900/30 text-green-400' : 'bg-stone/20 text-stone' }}">
                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-stone hover:text-cream text-xs uppercase tracking-widest">Editar</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-stone hover:text-red-400 text-xs uppercase tracking-widest">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-stone">No hay productos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-stone/10">
        {{ $products->withQueryString()->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
