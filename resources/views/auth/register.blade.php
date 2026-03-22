<x-guest-layout>

    <div class="anim-up mb-8">
        <h1 class="font-serif text-3xl text-carbon">Crear cuenta</h1>
        <p class="text-stone text-sm mt-1">Únete a EM Collective y empieza a comprar</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5 anim-up-2">
        @csrf

        {{-- Name --}}
        <div>
            <label class="auth-label" for="name">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="auth-input {{ $errors->has('name') ? 'error' : '' }}"
                   placeholder="Tu nombre"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="auth-label" for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="auth-input {{ $errors->has('email') ? 'error' : '' }}"
                   placeholder="tucorreo@email.com"
                   required autocomplete="username">
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="auth-label" for="password">Contraseña</label>
            <input id="password" type="password" name="password"
                   class="auth-input {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="Mínimo 8 caracteres"
                   required autocomplete="new-password">
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="auth-label" for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="auth-input {{ $errors->has('password_confirmation') ? 'error' : '' }}"
                   placeholder="Repite tu contraseña"
                   required autocomplete="new-password">
            @error('password_confirmation')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Terms note --}}
        <p class="text-[11px] text-stone leading-relaxed">
            Al registrarte aceptas nuestros
            <a href="#" class="underline hover:text-carbon">Términos de uso</a>
            y la
            <a href="#" class="underline hover:text-carbon">Política de privacidad</a>.
        </p>

        <button type="submit" class="auth-btn">Crear cuenta</button>

        {{-- Login link --}}
        <p class="text-center text-xs text-stone">
            ¿Ya tienes cuenta?&nbsp;
            <a href="{{ route('login') }}" class="text-carbon font-medium hover:text-blue transition-colors underline underline-offset-2">
                Inicia sesión
            </a>
        </p>
    </form>

</x-guest-layout>
