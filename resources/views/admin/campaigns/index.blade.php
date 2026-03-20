@extends('admin.layouts.admin')
@section('title', 'Campañas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-serif text-3xl font-light">Campañas de Email</h1>
    <a href="{{ route('admin.campaigns.create') }}" class="btn-admin btn-admin-primary">+ Nueva campaña</a>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone/10 text-stone text-xs uppercase tracking-widest">
                <th class="px-5 py-3 text-left">Nombre</th>
                <th class="px-5 py-3 text-left">Segmento</th>
                <th class="px-5 py-3 text-left">Enviados</th>
                <th class="px-5 py-3 text-left">Open rate</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaigns as $campaign)
            <tr class="table-row">
                <td class="px-5 py-3">
                    <p class="font-medium">{{ $campaign->name }}</p>
                    <p class="text-stone text-xs">{{ $campaign->subject }}</p>
                </td>
                <td class="px-5 py-3 text-stone text-xs uppercase tracking-wider">{{ $campaign->segment }}</td>
                <td class="px-5 py-3 text-stone">{{ $campaign->sent_count ?? 0 }}</td>
                <td class="px-5 py-3 text-stone">
                    @if($campaign->sent_count > 0)
                    {{ round(($campaign->open_count / $campaign->sent_count) * 100, 1) }}%
                    @else —
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-0.5
                        @if($campaign->status === 'sent') bg-green-900/30 text-green-400
                        @elseif($campaign->status === 'sending') bg-blue-900/30 text-blue-400
                        @else bg-stone/20 text-stone
                        @endif">
                        {{ ucfirst($campaign->status ?? 'draft') }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-3 items-center">
                        @if($campaign->status !== 'sent' && $campaign->status !== 'sending')
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-stone hover:text-cream text-xs">Editar</a>
                        <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST"
                              onsubmit="return confirm('¿Enviar esta campaña ahora a todos los destinatarios?')">
                            @csrf
                            <button type="submit" class="text-terracota hover:text-cream text-xs uppercase tracking-widest">Enviar</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar campaña?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-stone hover:text-red-400 text-xs">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-stone">Sin campañas.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($campaigns->hasPages())
    <div class="px-5 py-4 border-t border-stone/10">
        {{ $campaigns->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
