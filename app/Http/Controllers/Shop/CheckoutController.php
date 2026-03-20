<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderConfirmedMail;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService       $cartService,
        protected CheckoutService   $checkoutService,
        protected MercadoPagoService $mercadoPagoService,
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product.images', 'items.variant.size', 'items.variant.color', 'coupon']);

        if ($cart->is_empty) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $summary   = $this->cartService->getSummary($cart);
        $user      = auth()->user();
        $addresses = $user ? Address::where('user_id', $user->id)->get() : collect();

        return view('shop.checkout', compact('cart', 'summary', 'user', 'addresses'));
    }

    public function store(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCart();
        $cart->load('items');

        if ($cart->is_empty) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        try {
            $order = $this->checkoutService->createOrder($request->validated());

            // Guardar dirección si el usuario lo solicita
            if ($request->boolean('save_address') && auth()->check()) {
                Address::create([
                    'user_id'    => auth()->id(),
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'phone'      => $request->phone,
                    'dni'        => $request->dni,
                    'department' => $request->department,
                    'province'   => $request->province,
                    'district'   => $request->district,
                    'address'    => $request->address,
                    'reference'  => $request->reference,
                    'is_default' => false,
                ]);
            }

            // Enviar email de confirmación
            try {
                Mail::to($order->customer_email)->queue(new OrderConfirmedMail($order));
            } catch (\Exception $e) {
                // No interrumpir el flujo si el email falla
            }

            // Redirigir según método de pago
            if ($request->payment_method === 'mercadopago') {
                $order->load('items');
                $preference = $this->mercadoPagoService->createPreference($order);

                $initPoint = config('mercadopago.sandbox')
                    ? ($preference['sandbox_init_point'] ?? $preference['init_point'])
                    : $preference['init_point'];

                return redirect($initPoint);
            }

            // Contra entrega — ir directamente a éxito
            return redirect()->route('checkout.success', $order);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ocurrió un error al procesar tu pedido. Por favor intenta de nuevo.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');
        return view('shop.order-success', compact('order'));
    }

    public function failed(Order $order)
    {
        $order->load('items');
        return view('shop.order-failed', compact('order'));
    }

    public function pending(Order $order)
    {
        $order->load('items');
        return view('shop.order-pending', compact('order'));
    }
}
