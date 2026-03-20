@extends('admin.layouts.admin')
@section('title', isset($category) ? 'Editar Categoría' : 'Nueva Categoría')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-stone hover:text-cream">←</a>
    <h1 class="font-serif text-3xl font-light">{{ isset($category) ? 'Editar: ' . $category->name : 'Nueva Categoría' }}</h1>
</div>

<div class="max-w-xl">
    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST" enctype="multipart/form-data" class="card p-6 space-y-4">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div>
            <label class="form-label">Nombre *</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-input" required>
        </div>

        <div>
            <label class="form-label">Categoría padre</label>
            <select name="parent_id" class="form-input">
                <option value="">Sin padre (categoría principal)</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Descripción</label>
            <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="form-label">Imagen</label>
            @if(isset($category) && $category->image)
            <img src="{{ asset('storage/' . $category->image) }}" class="w-24 h-24 object-cover mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="form-input">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Orden</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" class="form-input">
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer pb-2">
                    <input type="checkbox" name="is_active" value="1" class="accent-terracota"
                           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                    <span class="text-sm text-stone">Activa</span>
                </label>
            </div>
        </div>

        <div>
            <label class="form-label">Meta título</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $category->meta_title ?? '') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Meta descripción</label>
            <textarea name="meta_description" rows="2" class="form-input">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-admin btn-admin-primary">
                {{ isset($category) ? 'Guardar cambios' : 'Crear categoría' }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin-ghost">Cancelar</a>
        </div>

        @if($errors->any())
        <div class="text-red-400 text-xs space-y-1 pt-2">
            @foreach($errors->all() as $e)
            <p>• {{ $e }}</p>
            @endforeach
        </div>
        @endif
    </form>
</div>
@endsection
