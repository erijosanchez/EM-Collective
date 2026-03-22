@extends('layouts.shop')
@section('title', 'Checkout | EM Collective')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12"
     x-data="{
        paymentMethod: '{{ old('payment_method', 'mercadopago') }}',
        loading: false,
        useAddress: '',
     }">

    <h1 class="font-serif text-2xl sm:text-4xl font-light mb-6 sm:mb-8">Finalizar Compra</h1>

    @if($errors->any())
    <div class="bg-terracota/10 border border-terracota/30 text-terracota text-sm p-4 mb-6 space-y-1">
        @foreach($errors->all() as $error)
        <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" @submit="loading = true">
        @csrf
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-10">

            {{-- Formulario --}}
            <div class="lg:col-span-2 space-y-8 order-2 lg:order-1">

                {{-- Datos personales --}}
                <div>
                    <h2 class="font-serif text-2xl font-light mb-6 pb-3 border-b border-stone/20">Datos de contacto</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Nombre *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user?->name ? explode(' ', $user->name)[0] : '') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Apellido *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user?->name ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Correo electrónico *</label>
                            <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Teléfono *</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user?->phone) }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">DNI</label>
                            <input type="text" name="dni" value="{{ old('dni') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" maxlength="12">
                        </div>
                    </div>
                </div>

                {{-- Dirección de envío --}}
                <div>
                    <h2 class="font-serif text-2xl font-light mb-6 pb-3 border-b border-stone/20">Dirección de envío</h2>

                    {{-- Seleccionar dirección guardada --}}
                    @if($addresses->count())
                    <div class="mb-6">
                        <label class="block text-xs uppercase tracking-widest mb-3">Mis direcciones guardadas</label>
                        <div class="space-y-2">
                            @foreach($addresses as $addr)
                            <label class="flex items-start gap-3 p-3 border border-stone/30 cursor-pointer hover:border-carbon transition-colors" :class="useAddress === '{{ $addr->id }}' ? 'border-carbon' : ''">
                                <input type="radio" name="use_address" value="{{ $addr->id }}"
                                       @change="useAddress = '{{ $addr->id }}'; $dispatch('fill-address', {{ json_encode($addr) }})"
                                       class="mt-0.5 accent-carbon">
                                <div class="text-sm">
                                    <p class="font-medium">{{ $addr->first_name }} {{ $addr->last_name }}</p>
                                    <p class="text-stone text-xs">{{ $addr->address }}, {{ $addr->district }}, {{ $addr->province }}, {{ $addr->department }}</p>
                                </div>
                            </label>
                            @endforeach
                            <label class="flex items-center gap-3 p-3 border border-stone/30 cursor-pointer hover:border-carbon" :class="useAddress === 'new' ? 'border-carbon' : ''">
                                <input type="radio" name="use_address" value="new" @change="useAddress = 'new'" class="accent-carbon">
                                <span class="text-sm">+ Nueva dirección</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <div class="grid sm:grid-cols-2 gap-4" @fill-address.window="
                        let a = $event.detail;
                        $el.querySelector('[name=department]').value = a.department;
                        $el.querySelector('[name=province]').value = a.province;
                        $el.querySelector('[name=district]').value = a.district;
                        $el.querySelector('[name=address]').value = a.address;
                        $el.querySelector('[name=reference]').value = a.reference || '';
                    ">
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Departamento *</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Provincia *</label>
                            <input type="text" name="province" value="{{ old('province') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest mb-2">Distrito *</label>
                            <input type="text" name="district" value="{{ old('district') }}"
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs uppercase tracking-widest mb-2">Dirección *</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   placeholder="Calle, número, piso/depto..."
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs uppercase tracking-widest mb-2">Referencia</label>
                            <input type="text" name="reference" value="{{ old('reference') }}"
                                   placeholder="Cerca al parque, color de la casa..."
                                   class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">
                        </div>
                    </div>

                    @auth
                    <label class="flex items-center gap-2 mt-4 cursor-pointer">
                        <input type="checkbox" name="save_address" value="1" class="accent-carbon">
                        <span class="text-sm text-stone">Guardar esta dirección para futuras compras</span>
                    </label>
                    @endauth
                </div>

                {{-- Notas --}}
                <div>
                    <label class="block text-xs uppercase tracking-widest mb-2">Notas del pedido</label>
                    <textarea name="notes" rows="3" placeholder="Instrucciones especiales para la entrega..."
                              class="w-full border border-stone/30 px-4 py-3 text-sm focus:outline-none focus:border-carbon">{{ old('notes') }}</textarea>
                </div>

                {{-- Método de pago --}}
                <div>
                    <h2 class="font-serif text-2xl font-light mb-6 pb-3 border-b border-stone/20">Método de pago</h2>
                    <div class="space-y-3">
                        {{-- Mercado Pago --}}
                        <label class="flex items-start gap-4 p-4 border-2 cursor-pointer transition-colors"
                               :class="paymentMethod === 'mercadopago' ? 'border-carbon' : 'border-stone/30 hover:border-stone'">
                            <input type="radio" name="payment_method" value="mercadopago"
                                   x-model="paymentMethod" class="mt-0.5 accent-carbon">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-sans font-medium text-sm">Mercado Pago</span>
                                    <span class="text-[10px] bg-stone/10 px-2 py-0.5 text-stone uppercase tracking-wider">Seguro</span>
                                </div>
                                <p class="text-stone text-xs">Tarjeta de crédito/débito, Yape, Plin y más. Procesado por Mercado Pago.</p>
                            </div>
                        </label>

                        {{-- Contra entrega --}}
                        @if(\App\Models\Setting::get('contra_entrega_enabled', '1'))
                        <label class="flex items-start gap-4 p-4 border-2 cursor-pointer transition-colors"
                               :class="paymentMethod === 'contra_entrega' ? 'border-carbon' : 'border-stone/30 hover:border-stone'">
                            <input type="radio" name="payment_method" value="contra_entrega"
                                   x-model="paymentMethod" class="mt-0.5 accent-carbon">
                            <div>
                                <span class="font-sans font-medium text-sm">Contra entrega</span>
                                <p class="text-stone text-xs mt-1">Paga en efectivo cuando recibas tu pedido. Solo disponible en Lima.</p>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Resumen lateral sticky --}}
            <div class="order-1 lg:order-2">
                <div class="bg-white border border-stone/10 p-4 sm:p-6 lg:sticky lg:top-24">
                    <h3 class="font-serif text-xl font-light mb-6">Tu pedido</h3>

                    <div class="space-y-4 max-h-48 sm:max-h-64 overflow-y-auto mb-6">
                        @foreach($cart->items as $item)
                        <div class="flex gap-3">
                            <div class="w-14 h-16 bg-stone/10 flex-shrink-0 overflow-hidden">
                                @if($item->product->primary_image)
                                <img src="{{ asset('storage/' . $item->product->primary_image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs leading-snug text-carbon truncate">{{ $item->product->name }}</p>
                                @if($item->variant)
                                <p class="text-stone text-xs">{{ $item->variant->label }}</p>
                                @endif
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-stone">x{{ $item->quantity }}</span>
                                    <span class="text-xs font-medium">S/ {{ number_format($item->line_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 text-sm border-t border-stone/20 pt-4">
                        <div class="flex justify-between text-stone">
                            <span>Subtotal</span>
                            <span>S/ {{ number_format($summary['subtotal'], 2) }}</span>
                        </div>
                        @if($summary['discount'] > 0)
                        <div class="flex justify-between text-terracota">
                            <span>Descuento</span>
                            <span>-S/ {{ number_format($summary['discount'], 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-stone">
                            <span>Envío</span>
                            <span>{{ $summary['shipping'] > 0 ? 'S/ ' . number_format($summary['shipping'], 2) : 'Gratis' }}</span>
                        </div>
                        <div class="flex justify-between font-medium text-base pt-3 border-t border-stone/20">
                            <span>Total</span>
                            <span>S/ {{ number_format($summary['total'], 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="btn-primary w-full text-center mt-6 relative">
                        <span x-show="!loading">
                            <span x-show="paymentMethod === 'mercadopago'">Ir a Mercado Pago →</span>
                            <span x-show="paymentMethod !== 'mercadopago'">Confirmar Pedido →</span>
                        </span>
                        <span x-show="loading">Procesando...</span>
                    </button>

                    <p class="text-stone text-xs text-center mt-4">
                        🔒 Tus datos están protegidos con encriptación SSL
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
