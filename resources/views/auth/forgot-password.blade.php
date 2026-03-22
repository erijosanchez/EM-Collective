<x-guest-layout>

    <div class="anim-up mb-8">
        <div class="w-10 h-10 flex items-center justify-center bg-blue/10 rounded-full mb-4">
            <svg class="w-5 h-5 text-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <h1 class="font-serif text-3xl text-carbon">Recuperar contraseña</h1>
        <p class="text-stone text-sm mt-2 leading-relaxed">
            Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>
    </div>

    {{-- Session status --}}
    @if(session('status'))
        <div class="mb-6 p-3 bg-sage/10 border-l-2 border-sage text-sage text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5 anim-up-2">
        @csrf

        <div>
            <label class="auth-label" for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="auth-input {{ $errors->has('email') ? 'error' : '' }}"
                   placeholder="tucorreo@email.com"
                   required autofocus>
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Enviar enlace de recuperación</button>

        <p class="text-center text-xs text-stone">
            <a href="{{ route('login') }}" class="text-carbon hover:text-blue transition-colors underline underline-offset-2">
                ← Volver al inicio de sesión
            </a>
        </p>
    </form>

</x-guest-layout>
