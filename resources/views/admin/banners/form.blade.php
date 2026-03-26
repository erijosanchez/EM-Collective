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
        {{-- ── Fondo del título ── --}}
        <div x-data="{
            bgColor:   '{{ old('text_bg_color',   $banner->text_bg_color   ?? '#000000') }}',
            bgOpacity: {{ old('text_bg_opacity', $banner->text_bg_opacity ?? 0) }},
            bgRadius:  '{{ old('text_bg_radius',  $banner->text_bg_radius  ?? 'md') }}',
            get boxStyle() {
                const radii = { none: '0', md: '0.5rem', lg: '9999px' };
                if (this.bgOpacity === 0) return '';
                const h = this.bgColor.replace('#','');
                const r = parseInt(h.slice(0,2),16), g = parseInt(h.slice(2,4),16), b = parseInt(h.slice(4,6),16);
                return `width:fit-content;max-width:100%;background:rgba(${r},${g},${b},${this.bgOpacity/100});padding:0.5rem 1rem;border-radius:${radii[this.bgRadius]}`;
            }
        }">
            <input type="hidden" name="text_bg_radius" :value="bgRadius">
            <label class="form-label">Fondo del título</label>

            {{-- Preview realista --}}
            <div class="mt-2 rounded-xl overflow-hidden relative flex items-center px-6 py-5"
                 style="background:linear-gradient(135deg,#3d3530 0%,#5a4035 50%,#3d3530 100%); min-height:100px">
                {{-- Simulamos el layout del hero --}}
                <div>
                    <p class="text-terracota text-[10px] uppercase tracking-widest mb-2">Nueva Colección</p>
                    <div :style="boxStyle" class="transition-all duration-200 mb-3">
                        <p class="text-cream font-serif text-xl leading-snug">Título del banner<br><em class="opacity-80">Subtítulo elegante</em></p>
                    </div>
                    <span class="inline-block border border-cream/60 text-cream text-[10px] px-3 py-1 uppercase tracking-widest">Explorar</span>
                </div>
            </div>

            {{-- Controles --}}
            <div class="mt-3 grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 items-center">
                {{-- Color --}}
                <p class="text-xs text-stone">Color</p>
                <div class="flex items-center gap-2">
                    <input type="color" name="text_bg_color" x-model="bgColor"
                           class="w-9 h-9 rounded-lg border border-stone/30 cursor-pointer p-0.5 bg-transparent flex-shrink-0">
                    {{-- Presets de color --}}
                    <div class="flex gap-1.5 flex-wrap">
                        @foreach(['#000000' => 'Negro', '#FFFFFF' => 'Blanco', '#7C3D2A' => 'Terracota', '#1C3A2A' => 'Verde', '#1A1A2E' => 'Azul'] as $hex => $name)
                        <button type="button" @click="bgColor='{{ $hex }}'"
                            :class="bgColor === '{{ $hex }}' ? 'ring-2 ring-offset-1 ring-terracota ring-offset-carbon' : ''"
                            class="w-6 h-6 rounded-full border border-stone/30 transition flex-shrink-0"
                            style="background:{{ $hex }}" title="{{ $name }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- Opacidad --}}
                <p class="text-xs text-stone">Opacidad</p>
                <div class="flex items-center gap-3">
                    <input type="range" name="text_bg_opacity" x-model.number="bgOpacity"
                           min="0" max="90" step="5"
                           class="flex-1 accent-terracota cursor-pointer h-1.5 rounded">
                    <span class="text-xs text-cream w-16 text-right flex-shrink-0"
                          x-text="bgOpacity === 0 ? 'Sin fondo' : bgOpacity + '%'"></span>
                </div>

                {{-- Border radius --}}
                <p class="text-xs text-stone">Bordes</p>
                <div class="flex gap-2">
                    @foreach(['none' => ['Cuadrado', 'M3 5h10v6H3z'], 'md' => ['Suave', 'M5 5h6a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z'], 'lg' => ['Píldora', 'M5 5h6a3 3 0 0 1 0 6H5a3 3 0 0 1 0-6z']] as $val => [$label, $path])
                    <button type="button" @click="bgRadius='{{ $val }}'"
                        :class="bgRadius === '{{ $val }}'
                            ? 'border-terracota bg-terracota/10 text-terracota'
                            : 'border-stone/30 text-stone hover:border-stone hover:text-cream'"
                        class="flex items-center gap-1.5 px-3 py-1.5 border rounded text-xs transition">
                        <svg viewBox="0 0 16 16" class="w-4 h-4 flex-shrink-0" fill="currentColor">
                            <path d="{{ $path }}"/>
                        </svg>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Presets rápidos --}}
            <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-stone/10">
                <span class="text-xs text-stone self-center mr-1">Presets:</span>
                @foreach([
                    ['Sin fondo',   '#000000', 0,  'md'],
                    ['Velo oscuro', '#000000', 40, 'md'],
                    ['Sólido',      '#000000', 80, 'md'],
                    ['Crema velo',  '#F5F0E8', 20, 'md'],
                    ['Terracota',   '#7C3D2A', 50, 'lg'],
                ] as [$name, $col, $op, $rad])
                <button type="button"
                    @click="bgColor='{{ $col }}'; bgOpacity={{ $op }}; bgRadius='{{ $rad }}'"
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
