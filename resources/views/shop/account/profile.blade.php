@extends('layouts.shop')
@section('title', 'Mi Perfil | EM Collective')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('account.index') }}" class="text-stone hover:text-carbon">←</a>
        <h1 class="font-serif text-4xl font-light">Mi Perfil</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm p-3 mb-6">{{ session('success') }}</div>
    @endif

    {{-- Datos personales --}}
    <div class="bg-white border border-stone/10 p-6 mb-6">
        <h3 class="font-serif text-xl mb-6">Datos personales</h3>
        <form action="{{ route('account.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Nombre completo *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Correo electrónico</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full border border-stone/20 px-4 py-3 text-sm bg-stone/5 text-stone">
                    <p class="text-stone text-xs mt-1">El correo no puede cambiarse.</p>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Teléfono</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Fecha de nacimiento</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}"
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Género</label>
                    <select name="gender" class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                        <option value="">Prefiero no decirlo</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="newsletter" value="1" class="accent-carbon"
                           {{ $user->newsletter ? 'checked' : '' }}>
                    <span class="text-sm text-stone">Suscribirme al newsletter para recibir ofertas y novedades</span>
                </label>
            </div>
            <button type="submit" class="btn-primary mt-6">Guardar cambios</button>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-white border border-stone/10 p-6">
        <h3 class="font-serif text-xl mb-6">Cambiar contraseña</h3>
        @if($errors->has('current_password'))
        <p class="text-terracota text-sm mb-4">{{ $errors->first('current_password') }}</p>
        @endif
        <form action="{{ route('account.password.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Contraseña actual *</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Nueva contraseña *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Confirmar nueva contraseña *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                </div>
            </div>
            <button type="submit" class="btn-primary mt-6">Cambiar contraseña</button>
        </form>
    </div>
</div>
@endsection
