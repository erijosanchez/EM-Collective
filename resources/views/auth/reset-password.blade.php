<x-guest-layout>

    <div class="anim-up mb-8">
        <h1 class="font-serif text-3xl text-carbon">Nueva contraseña</h1>
        <p class="text-stone text-sm mt-1">Elige una contraseña segura para tu cuenta</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5 anim-up-2">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label class="auth-label" for="email">Correo electrónico</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $request->email) }}"
                   class="auth-input {{ $errors->has('email') ? 'error' : '' }}"
                   required autofocus autocomplete="username">
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label class="auth-label" for="password">Nueva contraseña</label>
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
                   placeholder="Repite tu nueva contraseña"
                   required autocomplete="new-password">
            @error('password_confirmation')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Restablecer contraseña</button>
    </form>

</x-guest-layout>
