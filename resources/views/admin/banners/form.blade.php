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
        {{-- ── Posición del texto (grid 3×3 estilo Canva) ── --}}
        <div x-data="{
            ha: '{{ old('text_align',  $banner->text_align  ?? 'left') }}',
            va: '{{ old('text_valign', $banner->text_valign ?? 'middle') }}',
            label() {
                const v = { top:'Superior', middle:'Medio', bottom:'Inferior' };
                const h = { left:'Izquierda', center:'Centro', right:'Derecha' };
                return v[this.va] + ' · ' + h[this.ha];
            }
        }">
            <input type="hidden" name="text_align"  :value="ha">
            <input type="hidden" name="text_valign" :value="va">
            <label class="form-label">Posición del texto</label>
            <div class="mt-2 flex flex-col gap-1 w-fit p-2 rounded-xl border border-stone/20 bg-carbon/40">
                @php
                $cells = [
                    ['top','left'],    ['top','center'],    ['top','right'],
                    ['middle','left'], ['middle','center'], ['middle','right'],
                    ['bottom','left'], ['bottom','center'], ['bottom','right'],
                ];
                @endphp
                @foreach(array_chunk($cells, 3) as $row)
                <div class="flex gap-1">
                    @foreach($row as [$v, $h])
                    <button type="button"
                        @click="ha = '{{ $h }}'; va = '{{ $v }}'"
                        :class="ha === '{{ $h }}' && va === '{{ $v }}'
                            ? 'bg-terracota border-terracota text-cream'
                            : 'border-stone/20 text-stone hover:border-stone/50 hover:bg-stone/10 hover:text-cream'"
                        class="w-12 h-12 rounded-lg border transition-all duration-150 cursor-pointer flex
                            {{ $v === 'top'    ? 'items-start' : ($v === 'bottom' ? 'items-end'   : 'items-center') }}
                            {{ $h === 'left'   ? 'justify-start' : ($h === 'right' ? 'justify-end' : 'justify-center') }}
                            p-1.5">
                        <div class="flex flex-col gap-0.5
                            {{ $h === 'center' ? 'items-center' : ($h === 'right' ? 'items-end' : 'items-start') }}">
                            <div class="h-0.5 w-4 rounded-full bg-current opacity-90"></div>
                            <div class="h-0.5 w-2.5 rounded-full bg-current opacity-60"></div>
                            <div class="h-0.5 w-3 rounded-full bg-current opacity-40"></div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endforeach
            </div>
            <p class="text-stone text-xs mt-1.5" x-text="label()"></p>
        </div>

        {{-- ── Color del título ── --}}
        <div>
            <label class="form-label">Color del título</label>
            <div class="flex items-center gap-3 mt-1">
                <input type="color" name="text_color"
                       value="{{ old('text_color', $banner->text_color ?? '#F5F0E8') }}"
                       class="w-10 h-10 rounded-lg border border-stone/30 cursor-pointer p-0.5 bg-transparent">
                <span class="text-stone text-xs">Predeterminado: <code class="text-cream">#F5F0E8</code> (crema)</span>
            </div>
        </div>

        {{-- ── Tipografía del título ── --}}
        <div>
            <label class="form-label">Tipografía del título</label>
            <div class="flex gap-3 mt-1">
                @foreach(['serif' => ['Elegante', 'font-serif'], 'sans' => ['Moderno', 'font-sans']] as $val => [$label, $fontClass])
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="font_family" value="{{ $val }}" class="sr-only peer"
                           {{ old('font_family', $banner->font_family ?? 'serif') === $val ? 'checked' : '' }}>
                    <div class="text-center border border-stone/30 py-3 px-3 peer-checked:border-terracota peer-checked:bg-terracota/10 peer-checked:text-terracota transition rounded hover:border-stone cursor-pointer">
                        <span class="block text-2xl {{ $fontClass }} leading-none mb-1">Aa</span>
                        <span class="text-xs">{{ $label }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        {{-- ── Fondo del bloque de texto ── --}}
        <div x-data="{
            bgColor:   '{{ old('text_bg_color',   $banner->text_bg_color   ?? '#000000') }}',
            bgOpacity: {{ old('text_bg_opacity', $banner->text_bg_opacity ?? 0) }},
            get previewStyle() {
                if (this.bgOpacity === 0) return 'padding:0.75rem 1.25rem';
                const h = this.bgColor.replace('#','');
                const r = parseInt(h.slice(0,2),16), g = parseInt(h.slice(2,4),16), b = parseInt(h.slice(4,6),16);
                return `background:rgba(${r},${g},${b},${this.bgOpacity/100});padding:0.75rem 1.25rem;border-radius:0.5rem`;
            }
        }">
            <label class="form-label">Fondo del texto</label>

            {{-- Preview --}}
            <div class="mt-2 h-24 rounded-lg overflow-hidden relative flex items-center justify-center"
                 style="background: linear-gradient(135deg, #3d3530 0%, #6b5a4e 100%)">
                <div :style="previewStyle" class="transition-all duration-200">
                    <p class="text-cream font-serif text-lg leading-tight">Título del banner</p>
                    <p class="text-cream/70 text-xs mt-0.5">Subtítulo de ejemplo</p>
                </div>
            </div>

            <div class="mt-3 flex gap-4 items-end">
                <div>
                    <p class="text-xs text-stone mb-1">Color</p>
                    <input type="color" name="text_bg_color" x-model="bgColor"
                           class="w-10 h-10 rounded-lg border border-stone/30 cursor-pointer p-0.5 bg-transparent">
                </div>
                <div class="flex-1">
                    <p class="text-xs text-stone mb-1">
                        Opacidad —
                        <span class="text-cream" x-text="bgOpacity === 0 ? 'Sin fondo' : bgOpacity + '%'"></span>
                    </p>
                    <input type="range" name="text_bg_opacity" x-model.number="bgOpacity"
                           min="0" max="90" step="5"
                           class="w-full accent-terracota cursor-pointer">
                </div>
            </div>

            {{-- Presets rápidos --}}
            <div class="flex flex-wrap gap-1.5 mt-2">
                @foreach([
                    ['Sin fondo',    '#000000', 0],
                    ['Negro suave',  '#000000', 35],
                    ['Negro fuerte', '#000000', 60],
                    ['Blanco velo',  '#F5F0E8', 15],
                    ['Terracota',    '#7C3D2A', 40],
                ] as [$name, $col, $op])
                <button type="button"
                    @click="bgColor='{{ $col }}'; bgOpacity={{ $op }}"
                    class="text-xs px-2.5 py-1 rounded border border-stone/30 text-stone hover:border-stone hover:text-cream transition">
                    {{ $name }}
                </button>
                @endforeach
            </div>
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
