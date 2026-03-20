@extends('admin.layouts.admin')
@section('title', 'Cupones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Cupones</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn-admin btn-admin-primary">+ Nuevo cupón</a>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                <th class="px-5 py-3 text-left">Código</th>
                <th class="px-5 py-3 text-left">Tipo</th>
                <th class="px-5 py-3 text-left">Valor</th>
                <th class="px-5 py-3 text-left">Usos</th>
                <th class="px-5 py-3 text-left">Vence</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons as $coupon)
            <tr class="table-row">
                <td class="px-5 py-3 font-medium font-mono">{{ $coupon->code }}</td>
                <td class="px-5 py-3 text-stone">{{ $coupon->type === 'percentage' ? 'Porcentaje' : 'Fijo' }}</td>
                <td class="px-5 py-3">
                    {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'S/ ' . number_format($coupon->value, 2) }}
                </td>
                <td class="px-5 py-3 text-stone">
                    {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}
                </td>
                <td class="px-5 py-3 text-stone text-xs">
                    {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : '—' }}
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 {{ $coupon->is_valid ? 'bg-green-900/30 text-green-400' : 'bg-stone/20 text-stone' }}">
                        {{ $coupon->is_valid ? 'Válido' : 'Inválido' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-stone hover:text-cream text-xs">Editar</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-stone hover:text-red-400 text-xs">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-stone">Sin cupones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($coupons->hasPages())
    <div class="px-5 py-4 border-t border-stone/10">
        {{ $coupons->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
