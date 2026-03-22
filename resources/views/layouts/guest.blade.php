<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EM Collective') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        carbon: '#1A1A18',
                        cream:   '#F5F1EB',
                        accent:  '#B85C38',
                        sage:    '#4D7C5F',
                        urgency: '#D94035',
                        stone:   '#9E9589',
                        // legacy aliases
                        blue:    '#B85C38',
                        red:     '#D94035',
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
        body { font-family: 'DM Sans', sans-serif; }
        h1,h2,h3 { font-family: 'Cormorant Garamond', serif; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes brandDot {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50%       { transform: scale(1.4); opacity: 1; }
        }
        .anim-up   { animation: fadeSlideUp 0.55s ease forwards; }
        .anim-up-2 { animation: fadeSlideUp 0.55s 0.1s ease forwards; opacity: 0; }
        .anim-up-3 { animation: fadeSlideUp 0.55s 0.2s ease forwards; opacity: 0; }
        .anim-up-4 { animation: fadeSlideUp 0.55s 0.3s ease forwards; opacity: 0; }

        /* ── Auth inputs ────────────────────────────────────── */
        .auth-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #1A1A18;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            border-radius: 0;
        }
        .auth-input:focus {
            border-color: #B85C38;
            box-shadow: 0 0 0 3px rgba(184,92,56,0.12);
        }
        .auth-input::placeholder { color: #9CA3AF; }
        .auth-input.error { border-color: #E84B3A; }

        .auth-label {
            display: block;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6b7280;
            margin-bottom: 0.375rem;
        }
        .auth-error {
            font-size: 0.78rem;
            color: #E84B3A;
            margin-top: 0.3rem;
        }

        /* ── Auth button ────────────────────────────────────── */
        .auth-btn {
            width: 100%;
            padding: 0.9rem 1.5rem;
            background: #1A1A18;
            color: #F5F1EB;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: background 0.25s, transform 0.1s;
            position: relative;
            overflow: hidden;
        }
        .auth-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 30%, rgba(255,255,255,0.08) 50%, transparent 70%);
            background-size: 200% 100%;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .auth-btn:hover { background: #B85C38; }
        .auth-btn:hover::after { opacity: 1; animation: shimmerBtn 1.2s ease infinite; }
        .auth-btn:active { transform: scale(0.99); }
        @keyframes shimmerBtn {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }

        /* ── Brand panel decoration ─────────────────────────── */
        .brand-panel {
            background: #1A1A18;
            background-image:
                linear-gradient(rgba(245,241,235,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245,241,235,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .brand-orb-1 {
            position: absolute; top: -120px; right: -80px;
            width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.12), transparent 70%);
        }
        .brand-orb-2 {
            position: absolute; bottom: -100px; left: -60px;
            width: 280px; height: 280px; border-radius: 50%;
            background: radial-gradient(circle, rgba(77,124,95,0.15), transparent 70%);
        }
        .brand-stat-divider {
            width: 1px; background: rgba(156,163,175,0.2); align-self: stretch;
        }
    </style>
</head>
<body class="min-h-screen">
<div class="min-h-screen flex">

    {{-- ── Left: Brand Panel (desktop only) ──────────────────────── --}}
    <aside class="hidden lg:flex lg:w-5/12 xl:w-4/12 brand-panel flex-col justify-between p-10 xl:p-14 relative overflow-hidden">
        <div class="brand-orb-1"></div>
        <div class="brand-orb-2"></div>

        {{-- Logo --}}
        <div class="relative z-10">
            <a href="{{ route('home') }}" class="inline-block group">
                <p class="text-cream font-serif text-xl xl:text-2xl tracking-[0.35em] uppercase group-hover:text-blue transition-colors duration-300">
                    EM COLLECTIVE
                </p>
                <div class="h-px bg-stone/30 mt-2 w-full"></div>
                <p class="text-stone text-[9px] tracking-[0.45em] uppercase mt-1.5">Moda · Familia · Perú</p>
            </a>
        </div>

        {{-- Headline --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-6 h-px bg-blue"></div>
                <p class="text-blue text-[10px] tracking-[0.3em] uppercase">Bienvenido</p>
            </div>
            <h2 class="text-cream font-serif text-4xl xl:text-5xl leading-[1.15] mb-4">
                Moda que<br><em>conecta</em><br>generaciones.
            </h2>
            <p class="text-stone text-sm leading-relaxed max-w-xs">
                Colecciones cuidadas para mujer, hombre y niños —<br>
                con estilo peruano y calidad garantizada.
            </p>
        </div>

        {{-- Stats --}}
        <div class="relative z-10 flex items-center gap-6">
            <div class="text-center">
                <p class="text-cream font-serif text-2xl">500+</p>
                <p class="text-stone text-[9px] uppercase tracking-widest mt-0.5">Productos</p>
            </div>
            <div class="brand-stat-divider"></div>
            <div class="text-center">
                <p class="text-cream font-serif text-2xl">3K+</p>
                <p class="text-stone text-[9px] uppercase tracking-widest mt-0.5">Clientes</p>
            </div>
            <div class="brand-stat-divider"></div>
            <div class="text-center">
                <p class="text-cream font-serif text-2xl">100%</p>
                <p class="text-stone text-[9px] uppercase tracking-widest mt-0.5">Seguro</p>
            </div>
        </div>
    </aside>

    {{-- ── Right: Form Panel ───────────────────────────────────────── --}}
    <main class="flex-1 flex flex-col justify-center bg-cream px-6 py-12 sm:px-10 lg:px-14 xl:px-20">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-10 text-center">
            <a href="{{ route('home') }}">
                <p class="font-serif text-xl tracking-[0.3em] uppercase text-carbon">EM COLLECTIVE</p>
                <p class="text-stone text-[9px] tracking-[0.4em] uppercase mt-1">Moda · Familia · Perú</p>
            </a>
        </div>

        {{-- Form slot --}}
        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <div class="mt-10 text-center">
            <p class="text-stone text-xs">
                &copy; {{ date('Y') }} EM Collective &nbsp;·&nbsp;
                <a href="{{ route('home') }}" class="hover:text-carbon transition-colors">Volver a la tienda</a>
            </p>
        </div>

    </main>
</div>
</body>
</html>
