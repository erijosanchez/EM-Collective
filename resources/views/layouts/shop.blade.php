<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))</title>
    <meta name="description" content="@yield('description', \App\Models\Setting::get('seo_home_description', ''))">

    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="@yield('og_title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))">
    <meta property="og:description" content="@yield('og_description', \App\Models\Setting::get('seo_home_description', ''))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="@yield('og_image', asset('img/og-default.jpg'))">
    <meta property="og:site_name"   content="EM Collective">
    <meta property="og:locale"      content="es_PE">
    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', \App\Models\Setting::get('seo_home_title', 'EM Collective'))">
    <meta name="twitter:description" content="@yield('og_description', \App\Models\Setting::get('seo_home_description', ''))">
    <meta name="twitter:image"       content="@yield('og_image', asset('img/og-default.jpg'))">
    {{-- JSON-LD base --}}
    @yield('json_ld')

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
                        cream:     '#F5F1EB',
                        accent:    '#B85C38',
                        sage:      '#4D7C5F',
                        urgency:   '#D94035',
                        stone:     '#9E9589',
                        // legacy aliases
                        terracota: '#B85C38',
                        red:       '#D94035',
                        blue:      '#B85C38',
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
        :root {
            --clr-carbon:  #1A1A18;
            --clr-cream:   #F5F1EB;
            --clr-accent:  #B85C38;
            --clr-sage:    #4D7C5F;
            --clr-urgency: #D94035;
            --clr-stone:   #9E9589;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--clr-cream); color: var(--clr-carbon); }
        h1,h2,h3,h4 { font-family: 'Cormorant Garamond', serif; }
        .btn-primary {
            display: inline-block;
            background: var(--clr-carbon);
            color: var(--clr-cream);
            padding: 0.75rem 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
            border: 1px solid var(--clr-carbon);
        }
        .btn-primary:hover { background: var(--clr-accent); border-color: var(--clr-accent); }
        .btn-outline {
            display: inline-block;
            background: transparent;
            color: var(--clr-carbon);
            padding: 0.75rem 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid var(--clr-carbon);
        }
        .btn-outline:hover { background: var(--clr-carbon); color: var(--clr-cream); }
        .btn-sage {
            display: inline-block;
            background: var(--clr-sage);
            color: #fff;
            padding: 0.75rem 2rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 500;
            transition: background 0.2s;
            border: 1px solid var(--clr-sage);
        }
        .btn-sage:hover { background: #3d6349; border-color: #3d6349; }
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
        .mega-dropdown { background: var(--clr-carbon); color: var(--clr-cream); border-top: 1px solid rgba(156,163,175,0.15); }
        /* Cart badge */
        .cart-badge { background: var(--clr-accent); }
        /* Badge de oferta / urgencia */
        .badge-sale { background: var(--clr-urgency); color: #fff; }
        /* Badge "nuevo" */
        .badge-new { background: var(--clr-sage); color: #fff; }
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
<header class="sticky top-0 z-40 bg-cream border-b border-stone/20"
        x-data="{ mobileOpen: false, searchOpen: false, activeMenu: null }"
        @keydown.escape.window="activeMenu = null; searchOpen = false">

    {{-- Barra principal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Hamburger (mobile) --}}
            <button @click="mobileOpen = true" class="lg:hidden p-2 text-carbon" aria-label="Menú">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="font-serif text-2xl font-light tracking-widest text-carbon">
                EM COLLECTIVE
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-8">
                @foreach($navCategories as $cat)
                <div @mouseenter="activeMenu = '{{ $cat->slug }}'"
                     @mouseleave="activeMenu = null"
                     class="flex items-center h-16">
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="text-xs uppercase tracking-widest transition-colors py-1 border-b-2 border-transparent"
                       :class="activeMenu === '{{ $cat->slug }}' ? 'text-terracota border-terracota' : 'text-carbon hover:text-terracota'">
                        {{ $cat->name }}
                    </a>
                </div>
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-1">
                {{-- Buscar --}}
                <button @click="searchOpen = !searchOpen; activeMenu = null"
                        class="p-2 text-carbon hover:text-terracota transition-colors" aria-label="Buscar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Cuenta --}}
                @auth
                <a href="{{ route('account.index') }}" class="hidden sm:flex p-2 text-carbon hover:text-terracota transition-colors" aria-label="Mi cuenta">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:flex p-2 text-carbon hover:text-terracota transition-colors" aria-label="Iniciar sesión">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                @endauth

                {{-- Carrito --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-carbon hover:text-terracota transition-colors" aria-label="Carrito">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @if($cartCount > 0)
                    <span class="cart-badge absolute -top-1 -right-1 text-white w-4 h-4 rounded-full flex items-center justify-center text-[10px]">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Barra de búsqueda --}}
        <div x-show="searchOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none" class="pb-4">
            <form action="{{ route('product.search') }}" method="GET">
                <div class="flex items-center border-b border-carbon gap-2">
                    <svg class="w-4 h-4 text-stone flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" placeholder="Buscar productos..."
                           class="flex-1 bg-transparent py-2 text-sm focus:outline-none font-sans"
                           x-ref="searchInput"
                           @show.window="$nextTick(() => $refs.searchInput?.focus())">
                    <button type="button" @click="searchOpen = false" class="p-1 text-stone hover:text-carbon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Mega-menús desktop (fuera del trigger, anchos a todo el header) ── --}}
    @foreach($navCategories as $cat)
    @if($cat->children->count())
    <div x-show="activeMenu === '{{ $cat->slug }}'"
         @mouseenter="activeMenu = '{{ $cat->slug }}'"
         @mouseleave="activeMenu = null"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display:none"
         class="mega-dropdown w-full shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-start gap-12">
                {{-- Título de la categoría padre --}}
                <div class="flex-shrink-0 w-40">
                    <p class="text-stone text-xs uppercase tracking-widest mb-1">Explorar</p>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="font-serif text-2xl text-cream font-light hover:text-terracota transition-colors">
                        {{ $cat->name }}
                    </a>
                </div>
                {{-- Subcategorías --}}
                <div class="flex-1 grid grid-cols-3 gap-x-8 gap-y-3">
                    @foreach($cat->children as $child)
                    <a href="{{ route('category.show', $cat->slug) }}?sub={{ $child->slug }}"
                       class="group flex items-center gap-2 text-sm text-stone hover:text-cream transition-colors py-0.5">
                        <span class="block w-0 h-px bg-terracota group-hover:w-3 transition-all duration-200 flex-shrink-0"></span>
                        {{ $child->name }}
                    </a>
                    @endforeach
                </div>
                {{-- CTA --}}
                <div class="flex-shrink-0 text-right">
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="text-xs uppercase tracking-widest text-stone hover:text-cream transition-colors border-b border-stone/40 hover:border-cream pb-0.5">
                        Ver todo →
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    {{-- ── Mobile Drawer ── --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display:none"
         class="fixed inset-0 z-50 lg:hidden">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-carbon/60" @click="mobileOpen = false"></div>
        {{-- Panel --}}
        <div class="fixed left-0 top-0 bottom-0 w-72 bg-cream overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            <div class="flex items-center justify-between p-4 border-b border-stone/20">
                <a href="{{ route('home') }}" class="font-serif text-xl tracking-widest text-carbon">EM COLLECTIVE</a>
                <button @click="mobileOpen = false" class="p-2 text-stone hover:text-carbon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="p-4 space-y-1">
                @foreach($navCategories as $cat)
                <div x-data="{ open: false }">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('category.show', $cat->slug) }}"
                           class="flex-1 py-2.5 text-xs uppercase tracking-widest text-carbon hover:text-terracota transition-colors">
                            {{ $cat->name }}
                        </a>
                        @if($cat->children->count())
                        <button @click="open = !open" class="p-2 text-stone hover:text-carbon transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                    @if($cat->children->count())
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display:none"
                         class="pl-4 pb-2 space-y-0.5 border-l border-stone/20 ml-1">
                        @foreach($cat->children as $child)
                        <a href="{{ route('category.show', $cat->slug) }}?sub={{ $child->slug }}"
                           class="block py-2 text-sm text-stone hover:text-carbon transition-colors">
                            {{ $child->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach

                <div class="pt-4 mt-2 border-t border-stone/20 space-y-1">
                    @auth
                    <a href="{{ route('account.index') }}" class="block py-2.5 text-xs uppercase tracking-widest text-carbon hover:text-terracota transition-colors">Mi Cuenta</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-2.5 text-xs uppercase tracking-widest text-stone hover:text-carbon transition-colors">
                            Cerrar Sesión
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="block py-2.5 text-xs uppercase tracking-widest text-carbon hover:text-terracota transition-colors">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block py-2.5 text-xs uppercase tracking-widest text-stone hover:text-carbon transition-colors">Crear Cuenta</a>
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

{{-- ══════════════════════════════════════
     MODAL GLOBAL — Vista Rápida
══════════════════════════════════════ --}}
<div x-data="quickViewModal()"
     @quick-view.window="open($event.detail)"
     @keydown.escape.window="close()"
     x-show="isOpen"
     style="display:none"
     class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-0 sm:p-4">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-carbon/70 backdrop-blur-sm" @click="close()"></div>

    {{-- Panel --}}
    <div class="relative bg-cream w-full sm:max-w-2xl max-h-[92vh] overflow-y-auto rounded-t-2xl sm:rounded-none"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8">

        {{-- Handle mobile --}}
        <div class="sm:hidden flex justify-center pt-3 pb-0">
            <div class="w-10 h-1 bg-stone/30 rounded-full"></div>
        </div>

        {{-- Cerrar --}}
        <button @click="close()"
                class="absolute top-3 right-3 z-10 p-2 text-stone hover:text-carbon transition-colors bg-cream/80 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="grid grid-cols-1 sm:grid-cols-2">

            {{-- Imagen --}}
            <div class="aspect-[4/3] sm:aspect-[3/4] bg-stone/10 flex-shrink-0">
                <template x-if="product.image">
                    <img :src="product.image" :alt="product.name"
                         class="w-full h-full object-cover">
                </template>
                <template x-if="!product.image">
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-stone/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </template>
            </div>

            {{-- Info --}}
            <div class="p-6 flex flex-col gap-4">

                <div>
                    <h2 class="font-serif text-2xl font-light leading-tight" x-text="product.name"></h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="font-sans text-xl font-medium"
                              x-text="'S/ ' + (product.price || 0).toFixed(2)"></span>
                        <template x-if="product.isOnSale">
                            <span class="text-stone text-sm line-through"
                                  x-text="'S/ ' + (product.basePrice || 0).toFixed(2)"></span>
                        </template>
                        <template x-if="product.isOnSale">
                            <span class="bg-terracota/10 text-terracota text-xs px-2 py-0.5"
                                  x-text="'-' + product.discountPct + '%'"></span>
                        </template>
                    </div>
                </div>

                {{-- Colores --}}
                <template x-if="product.colors && product.colors.length">
                    <div>
                        <p class="text-xs uppercase tracking-widest mb-3">Color</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="color in product.colors" :key="color.id">
                                <button type="button"
                                        @click="selectedColor = color.id"
                                        :title="color.name"
                                        :class="selectedColor === color.id
                                            ? 'ring-2 ring-offset-2 ring-carbon'
                                            : 'hover:ring-1 hover:ring-stone'"
                                        class="w-9 h-9 rounded-full border border-stone/20 transition-all"
                                        :style="`background: ${color.hex}`">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Tallas --}}
                <template x-if="product.sizes && product.sizes.length">
                    <div>
                        <p class="text-xs uppercase tracking-widest mb-3">Talla</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="size in product.sizes" :key="size.id">
                                <button type="button"
                                        @click="selectedSize = size.id"
                                        :class="selectedSize === size.id
                                            ? 'bg-carbon text-cream border-carbon'
                                            : sizeInStock(size.id)
                                                ? 'border-stone/30 hover:border-carbon text-carbon'
                                                : 'border-stone/20 text-stone/40 line-through cursor-not-allowed'"
                                        class="px-3 py-2 border text-xs uppercase tracking-wider transition-all"
                                        x-text="size.name">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Stock --}}
                <template x-if="currentStock !== null">
                    <p class="text-xs" :class="currentStock > 5 ? 'text-stone' : 'text-terracota'">
                        <span x-show="currentStock > 10">✓ Disponible</span>
                        <span x-show="currentStock > 0 && currentStock <= 10">
                            ⚡ Solo <span x-text="currentStock"></span> disponibles
                        </span>
                        <span x-show="currentStock === 0">✕ Sin stock en esta combinación</span>
                    </p>
                </template>

                {{-- Botones --}}
                <div class="flex flex-col gap-2 mt-auto pt-2">
                    <button type="button"
                            @click="addToCart()"
                            :disabled="!canAdd || adding"
                            class="btn-primary w-full text-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!adding && canAdd">Agregar al carrito</span>
                        <span x-show="!adding && !canAdd">Sin stock</span>
                        <span x-show="adding">Agregando...</span>
                    </button>
                    <a :href="`/producto/${product.slug}`"
                       class="text-center text-stone text-xs uppercase tracking-widest hover:text-carbon underline underline-offset-4 py-1 transition-colors">
                        Ver todos los detalles →
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Toast de confirmación --}}
<div x-data="{ show: false, msg: '' }"
     @cart-added.window="msg = $event.detail; show = true; setTimeout(() => show = false, 3000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display:none"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] bg-carbon text-cream text-sm px-6 py-3 rounded-full shadow-xl whitespace-nowrap"
     x-text="msg">
</div>

<script>
function quickViewModal() {
    return {
        isOpen: false,
        product: {},
        selectedSize: null,
        selectedColor: null,
        adding: false,

        get currentVariant() {
            if (!this.product.variants?.length) return null;
            return this.product.variants.find(v =>
                (!this.selectedSize  || v.sizeId  == this.selectedSize) &&
                (!this.selectedColor || v.colorId == this.selectedColor)
            ) ?? null;
        },

        get currentStock() {
            if (!this.product.variants?.length) return null;
            if (this.currentVariant) return this.currentVariant.stock;
            if (this.selectedSize || this.selectedColor) return 0;
            return null;
        },

        get canAdd() {
            if (!this.product.variants?.length) return true;
            if (this.currentVariant) return this.currentVariant.stock > 0;
            // Ninguna variante seleccionada: hay stock en alguna?
            return this.product.variants.some(v => v.stock > 0);
        },

        sizeInStock(sizeId) {
            if (!this.product.variants) return true;
            return this.product.variants.some(v =>
                v.sizeId == sizeId && v.stock > 0 &&
                (!this.selectedColor || v.colorId == this.selectedColor)
            );
        },

        open(data) {
            this.product      = data;
            this.selectedSize  = null;
            this.selectedColor = null;
            this.adding        = false;
            this.isOpen        = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        async addToCart() {
            if (!this.canAdd || this.adding) return;
            this.adding = true;

            const form = new FormData();
            form.append('_token', document.querySelector('meta[name=csrf-token]').content);
            form.append('product_id', this.product.id);
            form.append('quantity', 1);
            if (this.currentVariant) form.append('variant_id', this.currentVariant.id);

            try {
                const res  = await fetch('/carrito/agregar', {
                    method: 'POST', body: form,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                if (json.success) {
                    this.close();
                    window.dispatchEvent(new CustomEvent('cart-added', {
                        detail: json.message || '¡Producto agregado al carrito!'
                    }));
                }
            } catch(e) {
                // Fallback: submit normal
                const f = document.createElement('form');
                f.method = 'POST'; f.action = '/carrito/agregar';
                ['_token','product_id','quantity'].forEach(k => {
                    const i = document.createElement('input');
                    i.type = 'hidden'; i.name = k;
                    i.value = k === '_token'
                        ? document.querySelector('meta[name=csrf-token]').content
                        : k === 'product_id' ? this.product.id : 1;
                    f.appendChild(i);
                });
                document.body.appendChild(f); f.submit();
            } finally {
                this.adding = false;
            }
        }
    }
}
</script>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')

{{-- ── WhatsApp Flotante ── --}}
@php $wa = \App\Models\Setting::get('store_whatsapp'); @endphp
@if($wa)
<a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}?text={{ urlencode('Hola, vi sus productos en la tienda y tengo una consulta 😊') }}"
   target="_blank" rel="noopener"
   id="wa-btn"
   aria-label="Escribir por WhatsApp"
   style="position:fixed;bottom:24px;right:24px;z-index:999;display:flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:12px 18px;border-radius:50px;box-shadow:0 4px 20px rgba(37,211,102,0.4);font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;text-decoration:none;transition:all 0.3s ease;opacity:0;transform:translateY(20px)">
    <svg style="width:22px;height:22px;flex-shrink:0" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    <span id="wa-label">¿Necesitas ayuda?</span>
</a>
<style>
    @keyframes waPop { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    @keyframes waPulse { 0%,100%{box-shadow:0 4px 20px rgba(37,211,102,0.4)} 50%{box-shadow:0 4px 30px rgba(37,211,102,0.7)} }
    #wa-btn { animation: waPop 0.5s 1.5s ease forwards, waPulse 2.5s 2s ease-in-out infinite; }
    #wa-btn:hover { background:#1da851 !important; transform:translateY(-2px) scale(1.03) !important; }
    @media(max-width:480px){ #wa-label{ display:none; } #wa-btn{ padding:14px !important; border-radius:50% !important; } }
</style>
@endif
</body>
</html>
