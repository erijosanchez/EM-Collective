@extends('admin.layouts.admin')
@section('title', isset($campaign) ? 'Editar Campaña' : 'Nueva Campaña')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.campaigns.index') }}" class="text-stone hover:text-cream">←</a>
    <h1 class="font-serif text-3xl font-light">{{ isset($campaign) ? 'Editar: ' . $campaign->name : 'Nueva Campaña' }}</h1>
</div>

<form action="{{ isset($campaign) ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}"
      method="POST" class="grid lg:grid-cols-3 gap-6">
    @csrf
    @if(isset($campaign)) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-4">
        <div class="card p-6 space-y-4">
            <div>
                <label class="form-label">Nombre interno *</label>
                <input type="text" name="name" value="{{ old('name', $campaign->name ?? '') }}" class="form-input" required placeholder="ej: Promo Verano 2026">
            </div>
            <div>
                <label class="form-label">Asunto del email *</label>
                <input type="text" name="subject" value="{{ old('subject', $campaign->subject ?? '') }}" class="form-input" required placeholder="ej: ¡Hasta 50% de descuento esta semana!">
            </div>
            <div>
                <label class="form-label">Segmento *</label>
                <select name="segment" class="form-input" required>
                    <option value="all" {{ old('segment', $campaign->segment ?? 'all') === 'all' ? 'selected' : '' }}>Todos (registrados + newsletter)</option>
                    <option value="newsletter" {{ old('segment', $campaign->segment ?? '') === 'newsletter' ? 'selected' : '' }}>Solo newsletter</option>
                    <option value="registered" {{ old('segment', $campaign->segment ?? '') === 'registered' ? 'selected' : '' }}>Solo registrados</option>
                    <option value="buyers" {{ old('segment', $campaign->segment ?? '') === 'buyers' ? 'selected' : '' }}>Solo compradores</option>
                </select>
            </div>
            <div>
                <label class="form-label">Contenido HTML *</label>
                <p class="text-stone text-xs mb-2">Puedes usar estas clases: <code class="text-terracota">btn-primary</code>, <code class="text-terracota">section</code>. El email se envuelve en el layout base automáticamente.</p>
                <textarea name="content" rows="16" class="form-input font-mono text-xs" required>{{ old('content', $campaign->content ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="card p-5">
            <div class="space-y-2">
                <button type="submit" class="btn-admin btn-admin-primary w-full justify-center">
                    {{ isset($campaign) ? 'Guardar cambios' : 'Crear campaña' }}
                </button>
                <a href="{{ route('admin.campaigns.index') }}" class="btn-admin btn-admin-ghost w-full justify-center block text-center">Cancelar</a>
            </div>
        </div>

        @if($errors->any())
        <div class="card p-4 border-red-900/50">
            <div class="text-red-400 text-xs space-y-1">
                @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
            </div>
        </div>
        @endif
    </div>
</form>
@endsection
