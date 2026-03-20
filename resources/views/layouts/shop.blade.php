<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))</title>
    <meta name="description" content="@yield('description', \App\Models\Setting::get('seo_home_description', ''))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        carbon:    '#1A1A18',
                        cream:     '#F5F0E8',
                        terracota: '#C4714A',
                        stone:     '#8A8880',
                    },
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'serif'],
                        sans:  ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #F5F0E8; color: #1A1A18; }
        h1,h2,h3,h4 { font-family: 'Cormorant Garamond', serif; }
        .btn-primary {
            display: inline-block;
            background: #1A1A18;
            color: #F5F0E8;
            padding: 0.75rem 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
            border: 1px solid #1A1A18;
        }
        .btn-primary:hover { background: #C4714A; border-color: #C4714A; }
        .btn-outline {
            display: inline-block;
            background: transparent;
            color: #1A1A18;
            padding: 0.75rem 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #1A1A18;
        }
        .btn-outline:hover { background: #1A1A18; color: #F5F0E8; }
        /* Animación fade up */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.5s ease forwards; }
        /* Hover imagen */
        .img-hover { transition: transform 0.4s ease; overflow: hidden; }
        .img-hover img { transition: transform 0.4s ease; }
        .img-hover:hover img { transform: scale(1.05); }
        /* Mega-menú */
        .mega-menu { display: none; position: absolute; left: 0; right: 0; top: 100%; background: #1A1A18; color: #F5F0E8; z-index: 50; }
        .mega-trigger:hover .mega-menu { display: block; }
        /* Cart badge */
        .cart-badge { background: #C4714A; }
    </style>
    @yield('head')
</head>
<body class="antialiased">

{{-- Announcement Bar --}}
@php $announcementActive = \App\Models\Setting::get('announcement_bar_active', '1'); $announcementText = \App\Models\Setting::get('announcement_bar_text', ''); @endphp
@if($announcementActive && $announcementText)
<div class="bg-carbon text-cream text-center py-2 text-xs tracking-widest uppercase font-sans">
    {{ $announcementText }}
</div>
@endif

{{-- NAVBAR --}}
<header class="sticky top-0 z-40 bg-cream border-b border-stone/20" x-data="{ mobileOpen: false, searchOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Mobile menu button --}}
            <button @click="mobileOpen = true" class="lg:hidden p-2 text-carbon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="font-serif text-2xl font-light tracking-widest text-carbon">
                EM COLLECTIVE
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center space-x-8">
                @foreach($navCategories as $cat)
                <div class="mega-trigger relative group">
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="text-xs uppercase tracking-widest text-carbon hover:text-terracota transition-colors py-5 block">
                        {{ $cat->name }}
                    </a>
                    @if($cat->children->count())
                    <div class="mega-menu">
                        <div class="max-w-7xl mx-auto px-6 py-6 grid grid-cols-4 gap-6">
                            @foreach($cat->children as $child)
                            <a href="{{ route('category.show', $cat->slug) }}?sub={{ $child->slug }}"
                               class="text-sm text-stone hover:text-cream transition-colors">
                                {{ $child->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center space-x-4">
                {{-- Search --}}
                <button @click="searchOpen = !searchOpen" class="p-2 text-carbon hover:text-terracota transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Account --}}
                @auth
                <a href="{{ route('account.index') }}" class="hidden sm:block p-2 text-carbon hover:text-terracota transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:block p-2 text-carbon hover:text-terracota transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                @endauth

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-carbon hover:text-terracota transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @if($cartCount > 0)
                    <span class="cart-badge absolute -top-1 -right-1 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center text-[10px]">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Search Bar --}}
        <div x-show="searchOpen" x-transition class="pb-4">
            <form action="{{ route('product.search') }}" method="GET">
                <div class="flex border-b border-carbon">
                    <input type="text" name="q" placeholder="Buscar productos..."
                           class="flex-1 bg-transparent py-2 text-sm focus:outline-none font-sans"
                           autofocus>
                    <button type="submit" class="p-2 text-stone hover:text-carbon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div x-show="mobileOpen" class="fixed inset-0 z-50 lg:hidden" @click.away="mobileOpen = false">
        <div class="fixed inset-0 bg-carbon/60" @click="mobileOpen = false"></div>
        <div class="fixed left-0 top-0 bottom-0 w-72 bg-cream overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b border-stone/20">
                <span class="font-serif text-xl tracking-widest">EM COLLECTIVE</span>
                <button @click="mobileOpen = false" class="p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="p-4 space-y-1">
                @foreach($navCategories as $cat)
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-2 text-xs uppercase tracking-widest text-carbon">
                        {{ $cat->name }}
                        @if($cat->children->count())
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        @endif
                    </button>
                    @if($cat->children->count())
                    <div x-show="open" class="pl-4 space-y-1 mt-1">
                        @foreach($cat->children as $child)
                        <a href="{{ route('category.show', $cat->slug) }}?sub={{ $child->slug }}"
                           class="block py-1.5 text-sm text-stone hover:text-carbon">{{ $child->name }}</a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach

                <div class="pt-4 border-t border-stone/20 space-y-2">
                    @auth
                    <a href="{{ route('account.index') }}" class="block py-2 text-xs uppercase tracking-widest">Mi Cuenta</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs uppercase tracking-widest text-stone">Cerrar Sesión</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="block py-2 text-xs uppercase tracking-widest">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block py-2 text-xs uppercase tracking-widest text-stone">Crear Cuenta</a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</header>

{{-- Flash Messages --}}
@if(session('success'))
<div class="bg-carbon text-cream text-sm text-center py-2 px-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-terracota text-white text-sm text-center py-2 px-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    {{ session('error') }}
</div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-carbon text-cream mt-16">
    {{-- Newsletter --}}
    <div class="border-b border-stone/20 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h3 class="font-serif text-3xl font-light mb-2">Únete a la comunidad</h3>
            <p class="text-stone text-sm mb-6">Recibe novedades, tendencias y descuentos exclusivos.</p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex max-w-md mx-auto gap-0">
                @csrf
                <input type="email" name="email" placeholder="tu@correo.com" required
                       class="flex-1 bg-transparent border border-stone/40 px-4 py-2.5 text-sm text-cream placeholder-stone/60 focus:outline-none focus:border-terracota">
                <button type="submit" class="btn-primary whitespace-nowrap !py-2.5 !border-cream">
                    Suscribir
                </button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
        {{-- Brand --}}
        <div class="col-span-2 md:col-span-1">
            <span class="font-serif text-2xl tracking-widest">EM COLLECTIVE</span>
            <p class="text-stone text-sm mt-3 leading-relaxed">
                {{ \App\Models\Setting::get('store_tagline', 'Moda editorial para toda la familia') }}
            </p>
            {{-- Social --}}
            <div class="flex gap-4 mt-4">
                @if($ig = \App\Models\Setting::get('social_instagram'))
                <a href="{{ $ig }}" class="text-stone hover:text-terracota transition-colors" target="_blank" rel="noopener">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                @endif
                @if($fb = \App\Models\Setting::get('social_facebook'))
                <a href="{{ $fb }}" class="text-stone hover:text-terracota transition-colors" target="_blank" rel="noopener">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                @endif
                @if($tt = \App\Models\Setting::get('social_tiktok'))
                <a href="{{ $tt }}" class="text-stone hover:text-terracota transition-colors" target="_blank" rel="noopener">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.77a4.85 4.85 0 01-1.02-.08z"/></svg>
                </a>
                @endif
            </div>
        </div>

        {{-- Links --}}
        <div>
            <h4 class="text-xs uppercase tracking-widest mb-4">Tienda</h4>
            <ul class="space-y-2 text-stone text-sm">
                @foreach($navCategories as $cat)
                <li><a href="{{ route('category.show', $cat->slug) }}" class="hover:text-cream transition-colors">{{ $cat->name }}</a></li>
                @endforeach
                <li><a href="{{ route('product.search') }}?on_sale=1" class="hover:text-cream transition-colors">Ofertas</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-xs uppercase tracking-widest mb-4">Ayuda</h4>
            <ul class="space-y-2 text-stone text-sm">
                <li><a href="#" class="hover:text-cream transition-colors">Guía de tallas</a></li>
                <li><a href="#" class="hover:text-cream transition-colors">Envíos y entregas</a></li>
                <li><a href="#" class="hover:text-cream transition-colors">Cambios y devoluciones</a></li>
                <li><a href="#" class="hover:text-cream transition-colors">Preguntas frecuentes</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-xs uppercase tracking-widest mb-4">Contacto</h4>
            <ul class="space-y-2 text-stone text-sm">
                @if($phone = \App\Models\Setting::get('store_phone'))
                <li>{{ $phone }}</li>
                @endif
                @if($wa = \App\Models\Setting::get('store_whatsapp'))
                <li><a href="https://wa.me/{{ $wa }}" target="_blank" class="hover:text-terracota transition-colors">WhatsApp</a></li>
                @endif
                @if($email = \App\Models\Setting::get('store_email'))
                <li><a href="mailto:{{ $email }}" class="hover:text-cream transition-colors">{{ $email }}</a></li>
                @endif
            </ul>

            {{-- Trust badges --}}
            <div class="mt-6 space-y-2">
                <div class="flex items-center gap-2 text-stone text-xs">
                    <svg class="w-4 h-4 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Envío a todo el Perú
                </div>
                <div class="flex items-center gap-2 text-stone text-xs">
                    <svg class="w-4 h-4 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pago 100% seguro
                </div>
                <div class="flex items-center gap-2 text-stone text-xs">
                    <svg class="w-4 h-4 text-terracota" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Cambios sin costo
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-stone/20 py-4">
        <p class="text-center text-stone text-xs">
            © {{ date('Y') }} EM Collective. Todos los derechos reservados.
        </p>
    </div>
</footer>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
