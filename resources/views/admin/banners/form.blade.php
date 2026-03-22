@extends('admin.layouts.admin')
@section('title', $banner->exists ? 'Editar Banner' : 'Nuevo Banner')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.banners.index') }}" class="text-stone hover:text-cream">←</a>
    <h1 class="font-serif text-3xl font-light">{{ $banner->exists ? 'Editar Banner' : 'Nuevo Banner' }}</h1>
</div>

<div class="max-w-2xl">
    <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
          method="POST" enctype="multipart/form-data" class="card p-6 space-y-4">
        @csrf
        @if($banner->exists) @method('PUT') @endif

        <div>
            <label class="form-label">Título *</label>
            <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" class="form-input" required>
        </div>
        <div>
            <label class="form-label">Subtítulo</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="form-input">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Texto del botón</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" class="form-input" placeholder="ej: Ver Colección">
            </div>
            <div>
                <label class="form-label">URL del botón</label>
                <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url ?? '') }}" class="form-input" placeholder="/categoria/mujer">
            </div>
        </div>
        <div>
            <label class="form-label">Posición *</label>
            <select name="position" class="form-input" required>
                <option value="hero" {{ old('position', $banner->position ?? 'hero') === 'hero' ? 'selected' : '' }}>Hero (inicio principal)</option>
                <option value="mid_home" {{ old('position', $banner->position ?? '') === 'mid_home' ? 'selected' : '' }}>Medio del inicio</option>
                <option value="sidebar" {{ old('position', $banner->position ?? '') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                <option value="popup" {{ old('position', $banner->position ?? '') === 'popup' ? 'selected' : '' }}>Popup</option>
                <option value="category_top" {{ old('position', $banner->position ?? '') === 'category_top' ? 'selected' : '' }}>Top de categoría</option>
            </select>
        </div>
        <div>
            <label class="form-label">Imagen desktop</label>
            @if($banner->exists && $banner->image)
            <img src="{{ asset('storage/' . $banner->image) }}" class="h-24 object-cover mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="form-input">
            <p class="text-stone text-xs mt-1">Recomendado: 1920×900px</p>
        </div>
        <div>
            <label class="form-label">Imagen mobile</label>
            @if($banner->exists && $banner->mobile_image)
            <img src="{{ asset('storage/' . $banner->mobile_image) }}" class="h-24 object-cover mb-2">
            @endif
            <input type="file" name="mobile_image" accept="image/*" class="form-input">
            <p class="text-stone text-xs mt-1">Recomendado: 750×900px</p>
        </div>
        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Orden</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0" class="form-input">
            </div>
            <div>
                <label class="form-label">Válido desde</label>
                <input type="date" name="starts_at" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d') ?? '') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Válido hasta</label>
                <input type="date" name="ends_at" value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d') ?? '') }}" class="form-input">
            </div>
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="accent-terracota"
                   {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-stone">Activo</span>
        </label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-admin btn-admin-primary">
                {{ $banner->exists ? 'Guardar cambios' : 'Crear banner' }}
            </button>
            <a href="{{ route('admin.banners.index') }}" class="btn-admin btn-admin-ghost">Cancelar</a>
        </div>
    </form>
</div>
@endsection
