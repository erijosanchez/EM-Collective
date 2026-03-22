<x-guest-layout>

    <div class="anim-up mb-8">
        <div class="w-12 h-12 flex items-center justify-center bg-blue/10 rounded-full mb-4">
            <svg class="w-6 h-6 text-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
        </div>
        <h1 class="font-serif text-3xl text-carbon">Verifica tu correo</h1>
        <p class="text-stone text-sm mt-2 leading-relaxed">
            ¡Gracias por registrarte! Hemos enviado un enlace de verificación a tu correo.
            Por favor revisa tu bandeja de entrada (y la carpeta de spam).
        </p>
    </div>

    @if(session('status') === 'verification-link-sent')
        <div class="mb-6 p-3 bg-sage/10 border-l-2 border-sage text-sage text-sm">
            Se ha enviado un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <div class="space-y-4 anim-up-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn">Reenviar correo de verificación</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full text-center text-xs text-stone hover:text-carbon transition-colors py-2">
                Cerrar sesión
            </button>
        </form>
    </div>

</x-guest-layout>
