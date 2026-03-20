@extends('admin.layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="font-serif text-3xl font-light">Dashboard</h1>
    <p class="text-stone text-sm mt-1">Resumen de actividad de EM Collective</p>
</div>

{{-- Stats cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card p-5">
        <p class="text-stone text-xs uppercase tracking-widest mb-2">Ventas hoy</p>
        <p class="font-serif text-3xl font-light">S/ {{ number_format($salesToday, 0) }}</p>
        @if($saleDelta !== null)
        <p class="text-xs mt-1 {{ $saleDelta >= 0 ? 'text-green-400' : 'text-red-400' }}">
            {{ $saleDelta >= 0 ? '↑' : '↓' }} {{ abs($saleDelta) }}% vs ayer
        </p>
        @endif
    </div>
    <div class="card p-5">
        <p class="text-stone text-xs uppercase tracking-widest mb-2">Pedidos hoy</p>
        <p class="font-serif text-3xl font-light">{{ $ordersToday }}</p>
    </div>
    <div class="card p-5">
        <p class="text-stone text-xs uppercase tracking-widest mb-2">Pendientes</p>
        <p class="font-serif text-3xl font-light text-terracota">{{ $pendingOrders }}</p>
    </div>
    <div class="card p-5">
        <p class="text-stone text-xs uppercase tracking-widest mb-2">Clientes</p>
        <p class="font-serif text-3xl font-light">{{ $totalCustomers }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    {{-- Gráfico ventas --}}
    <div class="card p-5 lg:col-span-2">
        <h3 class="font-serif text-lg mb-4">Ventas últimos 6 meses</h3>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    {{-- Top productos --}}
    <div class="card p-5">
        <h3 class="font-serif text-lg mb-4">Top 5 productos</h3>
        <div class="space-y-3">
            @foreach($topProducts as $i => $item)
            <div class="flex items-center gap-3">
                <span class="text-stone text-xs w-4">{{ $i + 1 }}</span>
                @if($item->product?->primary_image)
                <img src="{{ asset('storage/' . $item->product->primary_image) }}"
                     class="w-8 h-10 object-cover flex-shrink-0">
                @else
                <div class="w-8 h-10 bg-stone/20 flex-shrink-0"></div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-xs truncate">{{ $item->product?->name ?? 'Producto eliminado' }}</p>
                    <p class="text-stone text-[10px]">{{ $item->total_sold }} vendidos</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Pedidos recientes --}}
<div class="card">
    <div class="flex items-center justify-between px-5 py-4 border-b border-stone/10">
        <h3 class="font-serif text-lg">Pedidos recientes</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-terracota text-xs uppercase tracking-widest">Ver todos</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                    <th class="px-5 py-3 text-left">Pedido</th>
                    <th class="px-5 py-3 text-left">Cliente</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Estado</th>
                    <th class="px-5 py-3 text-left">Fecha</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr class="table-row">
                    <td class="px-5 py-3 font-medium">{{ $order->order_number }}</td>
                    <td class="px-5 py-3 text-stone">{{ $order->customer_name }}</td>
                    <td class="px-5 py-3">S/ {{ number_format($order->total, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 uppercase tracking-wider
                            @if($order->status === 'delivered') bg-green-900/30 text-green-400
                            @elseif($order->status === 'shipped') bg-purple-900/30 text-purple-400
                            @elseif($order->status === 'cancelled') bg-red-900/30 text-red-400
                            @else bg-yellow-900/30 text-yellow-400
                            @endif">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-stone text-xs">{{ $order->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-terracota hover:text-cream text-xs">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Ventas (S/)',
            data: @json($chartData),
            backgroundColor: '#C4714A',
            borderRadius: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { grid: { color: '#2a2a28' }, ticks: { color: '#8A8880', font: { size: 11 } } },
            y: { grid: { color: '#2a2a28' }, ticks: { color: '#8A8880', font: { size: 11 }, callback: v => 'S/ ' + v.toLocaleString() } }
        }
    }
});
</script>
@endsection
