<x-guest-layout>

    {{-- Session status --}}
    @if(session('status'))
        <div class="mb-6 p-3 bg-sage/10 border-l-2 border-sage text-sage text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="anim-up mb-8">
        <h1 class="font-serif text-3xl text-carbon">Iniciar sesión</h1>
        <p class="text-stone text-sm mt-1">Ingresa a tu cuenta para continuar comprando</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5 anim-up-2">
        @csrf

        {{-- Email --}}
        <div>
            <label class="auth-label" for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="auth-input {{ $errors->has('email') ? 'error' : '' }}"
                   placeholder="tucorreo@email.com"
                   required autofocus autocomplete="username">
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="auth-label" for="password">Contraseña</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-[11px] text-stone hover:text-blue transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                   class="auth-input {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="••••••••"
                   required autocomplete="current-password">
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 border-gray-300 rounded cursor-pointer accent-blue">
            <label for="remember_me" class="text-xs text-stone cursor-pointer select-none">
                Mantener sesión iniciada
            </label>
        </div>

        <button type="submit" class="auth-btn">Iniciar sesión</button>

        {{-- Register link --}}
        @if(Route::has('register'))
            <p class="text-center text-xs text-stone">
                ¿No tienes cuenta?&nbsp;
                <a href="{{ route('register') }}" class="text-carbon font-medium hover:text-blue transition-colors underline underline-offset-2">
                    Créala gratis
                </a>
            </p>
        @endif
    </form>

</x-guest-layout>
