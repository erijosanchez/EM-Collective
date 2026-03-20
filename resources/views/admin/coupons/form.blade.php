@extends('admin.layouts.admin')
@section('title', isset($coupon) ? 'Editar Cupón' : 'Nuevo Cupón')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.coupons.index') }}" class="text-stone hover:text-cream">←</a>
    <h1 class="font-serif text-3xl font-light">{{ isset($coupon) ? 'Editar: ' . $coupon->code : 'Nuevo Cupón' }}</h1>
</div>

<div class="max-w-2xl">
    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          method="POST" class="card p-6 space-y-4">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Código *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}"
                       class="form-input uppercase" required placeholder="ej: VERANO20">
            </div>
            <div>
                <label class="form-label">Tipo *</label>
                <select name="type" class="form-input" required>
                    <option value="percentage" {{ old('type', $coupon->type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Porcentaje (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Fijo (S/)</option>
                </select>
            </div>
            <div>
                <label class="form-label">Valor *</label>
                <input type="number" name="value" step="0.01" min="0"
                       value="{{ old('value', $coupon->value ?? '') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Descuento máximo (S/)</label>
                <input type="number" name="max_discount" step="0.01" min="0"
                       value="{{ old('max_discount', $coupon->max_discount ?? '') }}" class="form-input" placeholder="Sin límite">
            </div>
            <div>
                <label class="form-label">Monto mínimo (S/)</label>
                <input type="number" name="min_order_amount" step="0.01" min="0"
                       value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}" class="form-input" placeholder="Sin mínimo">
            </div>
            <div>
                <label class="form-label">Límite de usos</label>
                <input type="number" name="usage_limit" min="1"
                       value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" class="form-input" placeholder="Sin límite">
            </div>
            <div>
                <label class="form-label">Usos por usuario</label>
                <input type="number" name="usage_limit_per_user" min="1"
                       value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}" class="form-input" placeholder="Sin límite">
            </div>
            <div>
                <label class="form-label">Válido desde</label>
                <input type="date" name="starts_at"
                       value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d') ?? '') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Válido hasta</label>
                <input type="date" name="expires_at"
                       value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d') ?? '') }}" class="form-input">
            </div>
        </div>

        <div>
            <label class="form-label">Descripción</label>
            <input type="text" name="description" value="{{ old('description', $coupon->description ?? '') }}" class="form-input">
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="accent-terracota"
                   {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-stone">Activo</span>
        </label>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-admin btn-admin-primary">
                {{ isset($coupon) ? 'Guardar cambios' : 'Crear cupón' }}
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="btn-admin btn-admin-ghost">Cancelar</a>
        </div>

        @if($errors->any())
        <div class="text-red-400 text-xs space-y-1">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif
    </form>
</div>
@endsection
