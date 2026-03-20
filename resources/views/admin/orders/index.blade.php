@extends('admin.layouts.admin')
@section('title', 'Pedidos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Pedidos</h1>
</div>

{{-- Filtros --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar pedido, cliente..."
           class="form-input !w-48">
    <select name="status" class="form-input !w-40">
        <option value="">Todos los estados</option>
        @foreach($statusLabels as $val => $label)
        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="form-input !w-36">
        <option value="">Todos los pagos</option>
        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Pagado</option>
        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
        <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Fallido</option>
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input !w-36">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input !w-36">
    <button type="submit" class="btn-admin btn-admin-ghost">Filtrar</button>
    @if(request()->hasAny(['search','status','payment_status','date_from','date_to']))
    <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-ghost">Limpiar</a>
    @endif
</form>

{{-- Status tabs --}}
<div class="flex gap-4 mb-4 overflow-x-auto">
    @foreach($statusLabels as $val => $label)
    <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
       class="text-xs uppercase tracking-widest whitespace-nowrap pb-1 border-b-2 transition-colors
           {{ request('status') === $val ? 'border-terracota text-terracota' : 'border-transparent text-stone hover:text-cream' }}">
        {{ $label }}
        @if(isset($statusCounts[$val]) && $statusCounts[$val] > 0)
        <span class="ml-1 text-[10px]">({{ $statusCounts[$val] }})</span>
        @endif
    </a>
    @endforeach
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                <th class="px-5 py-3 text-left">Pedido</th>
                <th class="px-5 py-3 text-left">Cliente</th>
                <th class="px-5 py-3 text-left">Total</th>
                <th class="px-5 py-3 text-left">Pago</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3 text-left">Fecha</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="table-row">
                <td class="px-5 py-3 font-medium">{{ $order->order_number }}</td>
                <td class="px-5 py-3">
                    <p>{{ $order->customer_name }}</p>
                    <p class="text-stone text-xs">{{ $order->customer_email }}</p>
                </td>
                <td class="px-5 py-3">S/ {{ number_format($order->total, 2) }}</td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5 {{ $order->payment_status === 'paid' ? 'bg-green-900/30 text-green-400' : ($order->payment_status === 'failed' ? 'bg-red-900/30 text-red-400' : 'bg-yellow-900/30 text-yellow-400') }}">
                        {{ $order->payment_status === 'paid' ? 'Pagado' : ($order->payment_status === 'failed' ? 'Fallido' : 'Pendiente') }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5
                        @if($order->status === 'delivered') bg-green-900/30 text-green-400
                        @elseif($order->status === 'shipped') bg-purple-900/30 text-purple-400
                        @elseif($order->status === 'cancelled') bg-red-900/30 text-red-400
                        @elseif($order->status === 'processing') bg-blue-900/30 text-blue-400
                        @else bg-yellow-900/30 text-yellow-400
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td class="px-5 py-3 text-stone text-xs">{{ $order->created_at->format('d/m/y H:i') }}</td>
                <td class="px-5 py-3">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-terracota hover:text-cream text-xs uppercase tracking-widest">Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-stone">Sin pedidos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-stone/10">
        {{ $orders->withQueryString()->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
