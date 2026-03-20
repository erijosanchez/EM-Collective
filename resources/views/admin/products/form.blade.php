@extends('admin.layouts.admin')
@section('title', isset($product) ? 'Editar Producto' : 'Nuevo Producto')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-stone hover:text-cream">←</a>
        <h1 class="font-serif text-3xl font-light">{{ isset($product) ? 'Editar: ' . $product->name : 'Nuevo Producto' }}</h1>
    </div>
</div>

<form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data"
      x-data="{
        variants: {{ isset($product) ? $product->variants->map(fn($v)=>['id'=>$v->id,'size_id'=>$v->size_id,'color_id'=>$v->color_id,'sku'=>$v->sku,'stock'=>$v->stock,'price_modifier'=>$v->price_modifier])->toJson() : '[]' }},
        deleteVariants: [],
        addVariant() { this.variants.push({ id: null, size_id: '', color_id: '', sku: '', stock: 0, price_modifier: 0 }); },
        removeVariant(i) {
            if (this.variants[i].id) this.deleteVariants.push(this.variants[i].id);
            this.variants.splice(i, 1);
        }
      }">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Columna principal --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info básica --}}
            <div class="card p-6">
                <h3 class="font-serif text-lg mb-4">Información del producto</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                               class="form-input" required>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Categoría *</label>
                            <select name="category_id" class="form-input" required>
                                <option value="">Seleccionar...</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Marca</label>
                            <select name="brand_id" class="form-input">
                                <option value="">Sin marca</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="4" class="form-input">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Detalles / Especificaciones (HTML permitido)</label>
                        <textarea name="details" rows="4" class="form-input">{{ old('details', $product->details ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Precios --}}
            <div class="card p-6">
                <h3 class="font-serif text-lg mb-4">Precios</h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Precio base (S/) *</label>
                        <input type="number" name="base_price" step="0.01" min="0"
                               value="{{ old('base_price', $product->base_price ?? '') }}"
                               class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Precio oferta (S/)</label>
                        <input type="number" name="sale_price" step="0.01" min="0"
                               value="{{ old('sale_price', $product->sale_price ?? '') }}"
                               class="form-input" placeholder="Dejar vacío si no hay oferta">
                    </div>
                    <div>
                        <label class="form-label">Género *</label>
                        <select name="gender" class="form-input" required>
                            <option value="men" {{ old('gender', $product->gender ?? 'men') === 'men' ? 'selected' : '' }}>Hombre</option>
                            <option value="women" {{ old('gender', $product->gender ?? '') === 'women' ? 'selected' : '' }}>Mujer</option>
                            <option value="kids" {{ old('gender', $product->gender ?? '') === 'kids' ? 'selected' : '' }}>Niños</option>
                            <option value="unisex" {{ old('gender', $product->gender ?? '') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="form-input !w-64" placeholder="Opcional">
                </div>
            </div>

            {{-- Imágenes --}}
            <div class="card p-6" x-data="{ previews: [] }">
                <h3 class="font-serif text-lg mb-4">Imágenes (máx. 5)</h3>
                @if(isset($product) && $product->images->count())
                <div class="flex gap-3 flex-wrap mb-4">
                    @foreach($product->images as $img)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $img->path) }}" class="w-20 h-24 object-cover border border-stone/30">
                        @if($img->is_primary)
                        <span class="absolute bottom-0 left-0 right-0 bg-terracota text-white text-[8px] text-center py-0.5">Principal</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" class="form-input"
                       @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                <div class="flex gap-2 mt-3 flex-wrap">
                    <template x-for="src in previews" :key="src">
                        <img :src="src" class="w-20 h-24 object-cover border border-stone/30 opacity-60">
                    </template>
                </div>
                <p class="text-stone text-xs mt-2">Sube hasta {{ isset($product) ? 5 - $product->images->count() : 5 }} imagen(es) adicional(es).</p>
            </div>

            {{-- Variantes --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg">Variantes (Talla × Color)</h3>
                    <button type="button" @click="addVariant()" class="btn-admin btn-admin-ghost text-xs">+ Agregar</button>
                </div>

                {{-- Inputs hidden para delete --}}
                <template x-for="(id, i) in deleteVariants" :key="i">
                    <input type="hidden" name="delete_variants[]" :value="id">
                </template>

                <div class="space-y-3">
                    <template x-for="(v, i) in variants" :key="i">
                        <div class="grid grid-cols-5 gap-2 items-end">
                            <input type="hidden" :name="'variants[' + i + '][id]'" :value="v.id">
                            <div>
                                <label class="form-label">Talla</label>
                                <select :name="'variants[' + i + '][size_id]'" x-model="v.size_id" class="form-input">
                                    <option value="">—</option>
                                    @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Color</label>
                                <select :name="'variants[' + i + '][color_id]'" x-model="v.color_id" class="form-input">
                                    <option value="">—</option>
                                    @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">SKU</label>
                                <input type="text" :name="'variants[' + i + '][sku]'" x-model="v.sku" class="form-input" placeholder="Auto">
                            </div>
                            <div>
                                <label class="form-label">Stock</label>
                                <input type="number" :name="'variants[' + i + '][stock]'" x-model="v.stock" min="0" class="form-input">
                            </div>
                            <div class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="form-label">+Precio</label>
                                    <input type="number" :name="'variants[' + i + '][price_modifier]'" x-model="v.price_modifier" step="0.01" class="form-input">
                                </div>
                                <button type="button" @click="removeVariant(i)" class="text-red-400 hover:text-red-300 pb-2">✕</button>
                            </div>
                        </div>
                    </template>
                    <div x-show="variants.length === 0" class="text-stone text-sm text-center py-4">
                        Sin variantes. Haz clic en "+ Agregar" para crear combinaciones de talla y color.
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card p-6">
                <h3 class="font-serif text-lg mb-4">SEO</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Meta título</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Meta descripción</label>
                        <textarea name="meta_description" rows="2" class="form-input">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar derecha --}}
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="font-serif text-base mb-4">Publicación</h3>
                <div class="space-y-3">
                    <label class="flex items-center justify-between">
                        <span class="text-sm text-stone">Activo</span>
                        <input type="checkbox" name="is_active" value="1" class="accent-terracota w-4 h-4"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-sm text-stone">Destacado</span>
                        <input type="checkbox" name="is_featured" value="1" class="accent-terracota w-4 h-4"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                    </label>
                </div>
                <div class="mt-6 space-y-2">
                    <button type="submit" class="btn-admin btn-admin-primary w-full justify-center">
                        {{ isset($product) ? 'Guardar cambios' : 'Crear producto' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-ghost w-full justify-center block text-center">
                        Cancelar
                    </a>
                </div>
            </div>

            @if($errors->any())
            <div class="card p-5 border-red-900/50">
                <h4 class="text-red-400 text-sm font-medium mb-2">Errores:</h4>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-red-400 text-xs">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
