<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — EM Collective</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        carbon:    '#1A1A18',
                        sbg:       '#0E0E0C',
                        sidebar:   '#161614',
                        cream:     '#F5F1EB',
                        accent:    '#B85C38',
                        sage:      '#4D7C5F',
                        urgency:   '#D94035',
                        stone:     '#9E9589',
                        panel:     '#1E1E1C',
                        // legacy aliases
                        terracota: '#B85C38',
                        red:       '#D94035',
                        blue:      '#B85C38',
                    },
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'serif'],
                        sans:  ['"DM Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #0E0E0C; color: #F5F1EB; }
        h1,h2,h3 { font-family: 'Cormorant Garamond', serif; }
        .nav-link { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.75rem; color: #9CA3AF; letter-spacing: 0.08em; text-transform: uppercase; transition: color 0.15s, background 0.15s; border-radius: 0; }
        .nav-link:hover { color: #F5F1EB; background: #1E1E1C; }
        .nav-link.active { color: #B85C38; background: #1E1E1C; }
        .btn-admin { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1.25rem; font-size: 0.75rem; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 500; transition: all 0.15s; }
        .btn-admin-primary { background: #B85C38; color: #fff; }
        .btn-admin-primary:hover { background: #9e4a2a; }
        .btn-admin-success { background: #4D7C5F; color: #fff; }
        .btn-admin-success:hover { background: #3d6349; }
        .btn-admin-danger { background: #D94035; color: #fff; }
        .btn-admin-danger:hover { background: #bf3328; }
        .btn-admin-ghost { border: 1px solid #3a3a38; color: #9E9589; }
        .btn-admin-ghost:hover { border-color: #F5F1EB; color: #F5F1EB; }
        .form-input { background: #1E1E1C; border: 1px solid #3a3a38; color: #F5F1EB; padding: 0.5rem 0.75rem; font-size: 0.875rem; width: 100%; }
        .form-input:focus { outline: none; border-color: #B85C38; box-shadow: 0 0 0 2px rgba(184,92,56,0.2); }
        .form-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: #9CA3AF; display: block; margin-bottom: 0.375rem; }
        .card { background: #161614; border: 1px solid #2a2a28; }
        .table-row { border-bottom: 1px solid #1E1E1C; }
        .table-row:hover { background: #1E1E1C; }
        select.form-input option { background: #1E1E1C; }
        /* Status badges */
        .badge-pending  { background: rgba(232,75,58,0.15); color: #E84B3A; }
        .badge-active   { background: rgba(77,124,95,0.15); color: #4D7C5F; }
        .badge-blue     { background: rgba(37,99,235,0.15); color: #B85C38; }
    </style>
    @yield('head')
</head>
<body class="antialiased min-h-screen" x-data="{ sidebarOpen: false }">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="fixed top-0 left-0 bottom-0 w-56 bg-sidebar border-r border-stone/10 z-40 flex flex-col hidden lg:flex">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-stone/10">
            <a href="{{ route('admin.dashboard') }}" class="font-serif text-lg text-cream tracking-widest">EM COLLECTIVE</a>
            <p class="text-stone text-[10px] uppercase tracking-widest mt-0.5">Panel Admin</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4">
            {{-- General --}}
            <div class="px-3 mb-1">
                <p class="text-[9px] uppercase tracking-widest text-stone/60 px-3 mb-1">General</p>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} relative">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Pedidos
                    @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                    @if($pending > 0)
                    <span class="ml-auto bg-terracota text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ $pending }}</span>
                    @endif
                </a>
            </div>

            {{-- Catálogo --}}
            <div class="px-3 mt-3 mb-1">
                <p class="text-[9px] uppercase tracking-widest text-stone/60 px-3 mb-1">Catálogo</p>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Productos
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Categorías
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    Cupones
                </a>
            </div>

            {{-- Marketing --}}
            <div class="px-3 mt-3 mb-1">
                <p class="text-[9px] uppercase tracking-widest text-stone/60 px-3 mb-1">Marketing</p>
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Banners
                </a>
                <a href="{{ route('admin.campaigns.index') }}" class="nav-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Campañas
                </a>
            </div>

            {{-- Config --}}
            <div class="px-3 mt-3">
                <p class="text-[9px] uppercase tracking-widest text-stone/60 px-3 mb-1">Configuración</p>
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Ajustes
                </a>
            </div>
        </nav>

        {{-- Footer sidebar --}}
        <div class="p-4 border-t border-stone/10">
            <a href="{{ route('home') }}" target="_blank" class="text-stone hover:text-cream text-xs uppercase tracking-widest">
                Ver tienda →
            </a>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 lg:ml-56 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-sbg border-b border-stone/10 px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-stone hover:text-cream">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-stone text-xs">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:block text-stone hover:text-cream text-xs uppercase tracking-widest">Ver tienda</a>
                <span class="text-stone text-xs">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-stone hover:text-terracota text-xs uppercase tracking-widest">Salir</button>
                </form>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="bg-terracota/10 border-b border-terracota/30 text-terracota text-sm px-6 py-3" x-data x-init="setTimeout(() => $el.remove(), 4000)">
            ✓ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-900/20 border-b border-red-900/30 text-red-400 text-sm px-6 py-3" x-data x-init="setTimeout(() => $el.remove(), 5000)">
            ✗ {{ session('error') }}
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>

{{-- Mobile sidebar overlay --}}
<div x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden" @click.away="sidebarOpen = false">
    <div class="fixed inset-0 bg-black/60" @click="sidebarOpen = false"></div>
    <aside class="fixed left-0 top-0 bottom-0 w-56 bg-sidebar border-r border-stone/10 z-50 overflow-y-auto">
        <div class="px-5 py-5 border-b border-stone/10 flex items-center justify-between">
            <span class="font-serif text-lg text-cream tracking-widest">EM COLLECTIVE</span>
            <button @click="sidebarOpen = false" class="text-stone">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link">Pedidos</a>
            <a href="{{ route('admin.products.index') }}" class="nav-link">Productos</a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link">Categorías</a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link">Cupones</a>
            <a href="{{ route('admin.banners.index') }}" class="nav-link">Banners</a>
            <a href="{{ route('admin.campaigns.index') }}" class="nav-link">Campañas</a>
            <a href="{{ route('admin.settings') }}" class="nav-link">Ajustes</a>
        </nav>
    </aside>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
