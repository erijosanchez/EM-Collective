@extends('emails.layout')
@section('subject', '⚠️ Alerta de stock bajo — ' . $products->count() . ' producto(s) requieren atención')

@section('hero')
<p class="hero-eyebrow">⚠️ Alerta del sistema</p>
<h1 class="hero-title">{{ $products->count() }} producto{{ $products->count() > 1 ? 's' : '' }}<br>con stock bajo</h1>
<p class="hero-subtitle">Estos productos necesitan reposición pronto para evitar perder ventas.</p>
@endsection

@section('body')

{{-- Resumen rápido --}}
<div style="display:flex;gap:16px;margin-bottom:28px">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="33%" style="text-align:center;padding:16px;background:#FEF0EE;border-left:3px solid #E84B3A">
        <div style="font-size:28px;font-weight:700;color:#E84B3A;font-family:Georgia,serif">{{ $products->count() }}</div>
        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:#8A8880;margin-top:4px">Productos</div>
    </td>
    <td width="4px"></td>
    <td width="33%" style="text-align:center;padding:16px;background:#F5F0E8">
        <div style="font-size:28px;font-weight:700;color:#1A1A18;font-family:Georgia,serif">{{ $products->sum(fn($p) => $p->total_stock) }}</div>
        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:#8A8880;margin-top:4px">Unidades totales</div>
    </td>
    <td width="4px"></td>
    <td width="33%" style="text-align:center;padding:16px;background:#F5F0E8">
        <div style="font-size:28px;font-weight:700;color:#1A1A18;font-family:Georgia,serif">{{ $products->where('total_stock', 0)->count() }}</div>
        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:#8A8880;margin-top:4px">Sin stock</div>
    </td>
</tr>
</table>
</div>

{{-- Lista de productos --}}
<p class="section-title">Productos que requieren reposición</p>

<table class="product-table" cellpadding="0" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th style="text-align:left">Producto</th>
            <th style="text-align:center;width:80px">Stock</th>
            <th style="text-align:center;width:80px">Umbral</th>
            <th style="text-align:center;width:70px">Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        @php
            $stock = $product->total_stock;
            $threshold = $product->low_stock_threshold;
            $isOut = $stock === 0;
            $statusColor = $isOut ? '#E84B3A' : '#8A8880';
            $statusBg = $isOut ? '#FEF3EC' : '#F5F0E8';
            $statusText = $isOut ? '🔴 Sin stock' : '🟡 Bajo';
        @endphp
        <tr>
            <td style="padding:14px">
                <strong style="font-size:13px;color:#1A1A18;display:block">{{ $product->name }}</strong>
                @if($product->sku)
                <span style="font-size:10px;color:#8A8880;letter-spacing:0.1em">SKU: {{ $product->sku }}</span>
                @endif
                @if($product->category)
                <span style="font-size:10px;color:#8A8880"> · {{ $product->category->name }}</span>
                @endif
            </td>
            <td style="text-align:center;padding:14px">
                <strong style="font-size:16px;color:{{ $statusColor }}">{{ $stock }}</strong>
            </td>
            <td style="text-align:center;padding:14px;color:#8A8880;font-size:13px">{{ $threshold }}</td>
            <td style="text-align:center;padding:14px">
                <span style="display:inline-block;padding:4px 8px;background:{{ $statusBg }};color:{{ $statusColor }};font-size:10px;letter-spacing:0.05em;white-space:nowrap">
                    {{ $statusText }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- CTA al admin --}}
<div class="cta-wrap">
    <a href="{{ url('/admin/productos') }}" class="btn-main btn-shimmer">
        Ir al panel de productos →
    </a>
</div>

<p style="text-align:center;font-size:12px;color:#8A8880;margin-top:16px">
    Este reporte fue generado automáticamente el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
</p>

@endsection
