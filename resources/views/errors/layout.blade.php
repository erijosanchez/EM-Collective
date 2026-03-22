<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code', 'Error') — EM Collective</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent: @yield('accent', '#B85C38');
            --carbon: #1A1A18;
            --cream:  #F5F1EB;
            --stone:  #9E9589;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--carbon);
            color: var(--cream);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Canvas de partículas ───────────────────────────── */
        #particles-canvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Orbs de fondo con parallax ────────────────────── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            pointer-events: none;
            transition: transform 0.1s ease-out;
            z-index: 0;
        }
        .orb-1 {
            width: 500px; height: 500px;
            background: var(--accent);
            top: -150px; right: -100px;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: #4D7C5F;
            bottom: -100px; left: -80px;
        }

        /* ── Grid decorativo ────────────────────────────────── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(245,241,235,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245,241,235,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Contenido principal ────────────────────────────── */
        .error-wrap {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            width: 100%;
        }

        /* ── Número de error con glitch ─────────────────────── */
        .error-code {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(6rem, 20vw, 12rem);
            font-weight: 300;
            line-height: 1;
            color: var(--accent);
            position: relative;
            display: inline-block;
            animation: floatCode 4s ease-in-out infinite;
            margin-bottom: 0.5rem;
        }
        .error-code::before,
        .error-code::after {
            content: attr(data-text);
            position: absolute;
            inset: 0;
            font-family: inherit;
            font-size: inherit;
            font-weight: inherit;
            color: var(--accent);
        }
        .error-code::before {
            animation: glitch1 3.5s infinite;
            clip-path: polygon(0 0, 100% 0, 100% 40%, 0 40%);
            opacity: 0.5;
        }
        .error-code::after {
            animation: glitch2 3.5s infinite;
            clip-path: polygon(0 60%, 100% 60%, 100% 100%, 0 100%);
            opacity: 0.5;
        }

        @keyframes floatCode {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        @keyframes glitch1 {
            0%, 85%, 100% { transform: translate(0); opacity: 0; }
            86%  { transform: translate(-4px, 2px);  opacity: 0.6; }
            88%  { transform: translate(4px, -2px);  opacity: 0.6; }
            90%  { transform: translate(-2px, 0px);  opacity: 0.6; }
            92%  { transform: translate(0);           opacity: 0; }
        }
        @keyframes glitch2 {
            0%, 88%, 100% { transform: translate(0); opacity: 0; }
            89%  { transform: translate(4px, 1px);  opacity: 0.5; }
            91%  { transform: translate(-4px, -1px); opacity: 0.5; }
            93%  { transform: translate(0);           opacity: 0; }
        }

        /* ── Icono animado ──────────────────────────────────── */
        .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            opacity: 0;
            animation: fadeSlideUp 0.6s 0.2s ease forwards;
        }
        .error-icon svg {
            width: 100%;
            height: 100%;
            stroke: var(--accent);
            animation: iconPulse 3s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%, 100% { transform: scale(1);    filter: drop-shadow(0 0 0px var(--accent)); }
            50%       { transform: scale(1.08); filter: drop-shadow(0 0 12px var(--accent)); }
        }

        /* ── Textos ─────────────────────────────────────────── */
        .error-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.6rem, 5vw, 2.5rem);
            font-weight: 400;
            color: var(--cream);
            margin-bottom: 0.75rem;
            opacity: 0;
            animation: fadeSlideUp 0.6s 0.3s ease forwards;
        }
        .error-desc {
            font-size: 0.95rem;
            color: var(--stone);
            line-height: 1.7;
            max-width: 420px;
            margin: 0 auto 2.5rem;
            opacity: 0;
            animation: fadeSlideUp 0.6s 0.4s ease forwards;
        }

        /* ── Botones ────────────────────────────────────────── */
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeSlideUp 0.6s 0.5s ease forwards;
        }
        .btn-error-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 2rem;
            background: var(--accent);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-error-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transform: translateX(-100%);
            transition: transform 0.4s;
        }
        .btn-error-primary:hover::before { transform: translateX(100%); }
        .btn-error-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }

        .btn-error-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 2rem;
            background: transparent;
            color: var(--stone);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid rgba(158,149,137,0.3);
            transition: border-color 0.2s, color 0.2s, transform 0.2s;
        }
        .btn-error-ghost:hover {
            border-color: var(--cream);
            color: var(--cream);
            transform: translateY(-2px);
        }

        /* ── Línea decorativa ───────────────────────────────── */
        .error-line {
            width: 40px;
            height: 1px;
            background: var(--accent);
            margin: 1.5rem auto;
            opacity: 0;
            animation: expandLine 0.6s 0.25s ease forwards;
        }
        @keyframes expandLine {
            from { width: 0; opacity: 0; }
            to   { width: 40px; opacity: 1; }
        }

        /* ── Logo ───────────────────────────────────────────── */
        .error-logo {
            position: fixed;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            text-decoration: none;
            opacity: 0;
            animation: fadeSlideUp 0.5s 0.1s ease forwards;
        }
        .error-logo span {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: var(--cream);
            transition: color 0.2s;
        }
        .error-logo:hover span { color: var(--accent); }

        /* ── Animaciones de entrada ─────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Anillos decorativos ────────────────────────────── */
        .rings {
            position: absolute;
            inset: -60px;
            pointer-events: none;
            z-index: -1;
        }
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid var(--accent);
            opacity: 0;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0);
            animation: ringExpand 3s ease-out infinite;
        }
        .ring:nth-child(2) { animation-delay: 1s; }
        .ring:nth-child(3) { animation-delay: 2s; }
        @keyframes ringExpand {
            0%   { opacity: 0.3; transform: translate(-50%, -50%) scale(0.2); }
            100% { opacity: 0;   transform: translate(-50%, -50%) scale(1.4); }
        }
        .ring:nth-child(1) { width: 300px; height: 300px; }
        .ring:nth-child(2) { width: 300px; height: 300px; }
        .ring:nth-child(3) { width: 300px; height: 300px; }
    </style>
    @yield('extra_styles')
</head>
<body>
    {{-- Fondo --}}
    <div class="bg-grid"></div>
    <canvas id="particles-canvas"></canvas>
    <div class="orb orb-1" id="orb1"></div>
    <div class="orb orb-2" id="orb2"></div>

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="error-logo">
        <span>EM COLLECTIVE</span>
    </a>

    {{-- Contenido --}}
    <div class="error-wrap">
        <div style="position:relative; display:inline-block;">
            <div class="rings">
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
            </div>

            {{-- Icono --}}
            <div class="error-icon">
                @yield('icon')
            </div>
        </div>

        {{-- Código --}}
        <div class="error-code" data-text="@yield('code', '???')">@yield('code', '???')</div>

        <div class="error-line"></div>

        <h1 class="error-title">@yield('title', 'Algo salió mal')</h1>
        <p class="error-desc">@yield('description', 'Ha ocurrido un error inesperado.')</p>

        <div class="error-actions">
            @yield('actions')
        </div>
    </div>

    <script>
    // ── Partículas flotantes ─────────────────────────────────
    (function() {
        const canvas = document.getElementById('particles-canvas');
        const ctx    = canvas.getContext('2d');
        let W, H, particles = [];

        const accent = '{{ Str::of(config("app.name"))->is("*") ? "#B85C38" : "#B85C38" }}';

        function resize() {
            W = canvas.width  = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }

        function Particle() {
            this.x    = Math.random() * W;
            this.y    = Math.random() * H;
            this.r    = Math.random() * 1.5 + 0.5;
            this.vx   = (Math.random() - 0.5) * 0.3;
            this.vy   = (Math.random() - 0.5) * 0.3;
            this.life = Math.random();
            this.maxLife = Math.random() * 0.5 + 0.2;
        }

        Particle.prototype.update = function() {
            this.x += this.vx;
            this.y += this.vy;
            this.life += 0.003;
            if (this.life > this.maxLife) Object.assign(this, new Particle());
            if (this.x < 0 || this.x > W) this.vx *= -1;
            if (this.y < 0 || this.y > H) this.vy *= -1;
        };

        function init() {
            resize();
            particles = Array.from({length: 60}, () => new Particle());
        }

        function draw() {
            ctx.clearRect(0, 0, W, H);
            particles.forEach(p => {
                p.update();
                const alpha = Math.sin(p.life / p.maxLife * Math.PI) * 0.5;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(184,92,56,${alpha})`;
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }

        window.addEventListener('resize', resize);
        init();
        draw();
    })();

    // ── Parallax en orbs con el mouse ────────────────────────
    (function() {
        const orb1 = document.getElementById('orb1');
        const orb2 = document.getElementById('orb2');

        document.addEventListener('mousemove', e => {
            const x = (e.clientX / window.innerWidth  - 0.5);
            const y = (e.clientY / window.innerHeight - 0.5);
            orb1.style.transform = `translate(${x * 30}px, ${y * 30}px)`;
            orb2.style.transform = `translate(${-x * 20}px, ${-y * 20}px)`;
        });
    })();

    // ── Efecto ripple en botones ─────────────────────────────
    document.querySelectorAll('.btn-error-primary').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position:absolute; border-radius:50%;
                background:rgba(255,255,255,0.3);
                width:10px; height:10px;
                top:${e.clientY - rect.top - 5}px;
                left:${e.clientX - rect.left - 5}px;
                transform:scale(0); pointer-events:none;
                animation: rippleAnim 0.5s ease forwards;
            `;
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });

    const style = document.createElement('style');
    style.textContent = `@keyframes rippleAnim {
        to { transform: scale(30); opacity: 0; }
    }`;
    document.head.appendChild(style);
    </script>
    @stack('scripts')
</body>
</html>
