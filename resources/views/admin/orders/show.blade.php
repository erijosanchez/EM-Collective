@extends('admin.layouts.admin')
@section('title', 'Pedido ' . $order->order_number)

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-stone hover:text-cream">←</a>
    <h1 class="font-serif text-3xl font-light">{{ $order->order_number }}</h1>
    <span class="text-xs px-2 py-1 uppercase tracking-wider
        @if($order->status === 'delivered') bg-green-900/30 text-green-400
        @elseif($order->status === 'shipped') bg-purple-900/30 text-purple-400
        @elseif($order->status === 'cancelled') bg-red-900/30 text-red-400
        @else bg-yellow-900/30 text-yellow-400
        @endif">
        {{ $order->status_label }}
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Items --}}
        <div class="card p-5">
            <h3 class="font-serif text-lg mb-4">Productos</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex gap-4">
                    @if($item->product_image)
                    <img src="{{ asset('storage/' . $item->product_image) }}" class="w-16 h-20 object-cover flex-shrink-0">
                    @else
                    <div class="w-16 h-20 bg-stone/20 flex-shrink-0"></div>
                    @endif
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ $item->product_name }}</p>
                        @if($item->variant_label)
                        <p class="text-stone text-xs">{{ $item->variant_label }}</p>
                        @endif
                        <p class="text-stone text-xs">SKU: {{ $item->product_sku }}</p>
                        <div class="flex justify-between mt-1 text-sm">
                            <span class="text-stone">× {{ $item->quantity }} × S/ {{ number_format($item->unit_price, 2) }}</span>
                            <span class="font-medium">S/ {{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="border-t border-stone/10 mt-4 pt-4 space-y-1.5 text-sm">
                <div class="flex justify-between text-stone"><span>Subtotal</span><span>S/ {{ number_format($order->subtotal, 2) }}</span></div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-terracota"><span>Descuento</span><span>-S/ {{ number_format($order->discount_amount, 2) }}</span></div>
                @endif
                <div class="flex justify-between text-stone"><span>Envío</span><span>{{ $order->shipping_cost > 0 ? 'S/ ' . number_format($order->shipping_cost, 2) : 'Gratis' }}</span></div>
                <div class="flex justify-between font-medium text-base border-t border-stone/10 pt-2 mt-2"><span>Total</span><span>S/ {{ number_format($order->total, 2) }}</span></div>
            </div>
        </div>

        {{-- Update estado --}}
        <div class="card p-5">
            <h3 class="font-serif text-lg mb-4">Actualizar estado</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Estado del pedido</label>
                        <select name="status" class="form-input">
                            @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Código de tracking</label>
                        <input type="text" name="tracking_code" value="{{ old('tracking_code', $order->tracking_code) }}" class="form-input" placeholder="Ej: PE123456789">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Notas internas</label>
                        <textarea name="admin_notes" rows="3" class="form-input">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary mt-4">Guardar cambios</button>
            </form>
        </div>
    </div>

    {{-- Info lateral --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h4 class="font-serif text-base mb-3">Cliente</h4>
            <p class="text-sm font-medium">{{ $order->customer_name }}</p>
            <p class="text-stone text-xs">{{ $order->customer_email }}</p>
            <p class="text-stone text-xs">{{ $order->customer_phone }}</p>
            @if($order->customer_dni)
            <p class="text-stone text-xs">DNI: {{ $order->customer_dni }}</p>
            @endif
        </div>

        <div class="card p-5">
            <h4 class="font-serif text-base mb-3">Envío</h4>
            <p class="text-xs text-stone leading-relaxed">
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_district }}, {{ $order->shipping_province }}<br>
                {{ $order->shipping_department }}
            </p>
            @if($order->shipping_reference)
            <p class="text-xs text-stone mt-2">Ref: {{ $order->shipping_reference }}</p>
            @endif
            @if($order->tracking_code)
            <p class="text-xs mt-2"><strong>Tracking:</strong> {{ $order->tracking_code }}</p>
            @endif
        </div>

        <div class="card p-5">
            <h4 class="font-serif text-base mb-3">Pago</h4>
            <p class="text-sm">{{ $order->payment_method === 'mercadopago' ? 'Mercado Pago' : 'Contra entrega' }}</p>
            <span class="inline-block mt-2 text-xs px-2 py-0.5 {{ $order->payment_status === 'paid' ? 'bg-green-900/30 text-green-400' : 'bg-yellow-900/30 text-yellow-400' }}">
                {{ $order->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}
            </span>
            @if($order->payment_reference)
            <p class="text-xs text-stone mt-2">Ref: {{ $order->payment_reference }}</p>
            @endif
        </div>

        <div class="card p-5">
            <h4 class="font-serif text-base mb-3">Tiempos</h4>
            <div class="space-y-1 text-xs text-stone">
                <p>Creado: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                @if($order->shipped_at)
                <p>Enviado: {{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                @endif
                @if($order->delivered_at)
                <p>Entregado: {{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
