<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class CartService
{
    // ─── Obtener/Crear Carrito ────────────────────────────────────────────

    public function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['session_id' => null]
            );
        }

        $sessionId = Session::get('cart_session_id');

        if (!$sessionId) {
            $sessionId = 'guest_' . uniqid();
            Session::put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null]
        );
    }

    // ─── Agregar ítem ─────────────────────────────────────────────────────

    public function add(int $productId, int $quantity = 1, ?int $variantId = null): array
    {
        $product = Product::active()->findOrFail($productId);

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)
                ->where('id', $variantId)
                ->where('is_active', true)
                ->firstOrFail();

            if ($variant->stock < $quantity) {
                return ['success' => false, 'message' => 'Stock insuficiente.'];
            }
        }

        $cart = $this->getCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $newQty = $item->quantity + $quantity;

            if ($variantId && isset($variant) && $variant->stock < $newQty) {
                return ['success' => false, 'message' => 'No hay suficiente stock disponible.'];
            }

            $item->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_id'         => $productId,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        return ['success' => true, 'message' => 'Producto agregado al carrito.', 'count' => $this->getItemCount()];
    }

    // ─── Actualizar cantidad ──────────────────────────────────────────────

    public function update(int $itemId, int $quantity): array
    {
        $cart = $this->getCart();
        $item = CartItem::where('id', $itemId)->where('cart_id', $cart->id)->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();
            return ['success' => true, 'message' => 'Ítem eliminado.', 'removed' => true];
        }

        if ($item->product_variant_id) {
            $variant = $item->variant;
            if ($variant && $variant->stock < $quantity) {
                return ['success' => false, 'message' => 'Stock insuficiente.'];
            }
        }

        $item->update(['quantity' => $quantity]);

        return ['success' => true, 'message' => 'Cantidad actualizada.', 'removed' => false];
    }

    // ─── Eliminar ítem ────────────────────────────────────────────────────

    public function remove(int $itemId): array
    {
        $cart = $this->getCart();
        $item = CartItem::where('id', $itemId)->where('cart_id', $cart->id)->first();

        if (!$item) {
            return ['success' => false, 'message' => 'Ítem no encontrado.'];
        }

        $item->delete();

        return ['success' => true, 'message' => 'Producto eliminado del carrito.', 'count' => $this->getItemCount()];
    }

    // ─── Cupones ──────────────────────────────────────────────────────────

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon || !$coupon->is_valid) {
            return ['success' => false, 'message' => 'Cupón inválido o expirado.'];
        }

        $cart    = $this->getCart();
        $summary = $this->getSummary($cart);

        if ($coupon->min_order_amount && $summary['subtotal'] < $coupon->min_order_amount) {
            return [
                'success' => false,
                'message' => 'El pedido mínimo para este cupón es S/ ' . number_format($coupon->min_order_amount, 2),
            ];
        }

        $cart->update(['coupon_id' => $coupon->id]);

        return ['success' => true, 'message' => 'Cupón aplicado correctamente.'];
    }

    public function removeCoupon(): void
    {
        $this->getCart()->update(['coupon_id' => null]);
    }

    // ─── Resumen del carrito ──────────────────────────────────────────────

    public function getSummary(?Cart $cart = null): array
    {
        $cart = $cart ?? $this->getCart();
        $cart->load(['items.product', 'items.variant.size', 'items.variant.color', 'coupon']);

        $subtotal = $cart->subtotal;
        $discount = $cart->discount_amount;
        $afterDiscount = $subtotal - $discount;

        // Cálculo de envío
        $freeThreshold = (float) Setting::get('shipping_free_threshold', 150);
        $shippingCost  = $afterDiscount >= $freeThreshold ? 0 : (float) Setting::get('shipping_default_cost', 12);

        $total = $afterDiscount + $shippingCost;

        return [
            'subtotal'         => round($subtotal, 2),
            'discount'         => round($discount, 2),
            'shipping'         => round($shippingCost, 2),
            'total'            => round($total, 2),
            'free_threshold'   => $freeThreshold,
            'missing_for_free' => max(0, $freeThreshold - $afterDiscount),
            'coupon'           => $cart->coupon,
            'items_count'      => $cart->total_items,
        ];
    }

    // ─── Contador de ítems ────────────────────────────────────────────────

    public function getItemCount(): int
    {
        try {
            $cart = $this->getCart();
            return CartItem::where('cart_id', $cart->id)->sum('quantity');
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ─── Vaciar carrito ───────────────────────────────────────────────────

    public function clear(): void
    {
        $cart = $this->getCart();
        CartItem::where('cart_id', $cart->id)->delete();
        $cart->update(['coupon_id' => null]);
    }

    // ─── Merge carrito invitado → usuario ────────────────────────────────

    public function mergeGuestCart(int $userId): void
    {
        $sessionId = Session::get('cart_session_id');
        if (!$sessionId) return;

        $guestCart = Cart::where('session_id', $sessionId)->where('user_id', null)->first();
        if (!$guestCart || $guestCart->items->isEmpty()) return;

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guestCart->items as $guestItem) {
            $existing = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
            } else {
                CartItem::create([
                    'cart_id'            => $userCart->id,
                    'product_id'         => $guestItem->product_id,
                    'product_variant_id' => $guestItem->product_variant_id,
                    'quantity'           => $guestItem->quantity,
                ]);
            }
        }

        // Si el carrito invitado tenía cupón y el usuario no tiene, transferirlo
        if ($guestCart->coupon_id && !$userCart->coupon_id) {
            $userCart->update(['coupon_id' => $guestCart->coupon_id]);
        }

        $guestCart->items()->delete();
        $guestCart->delete();
        Session::forget('cart_session_id');
    }
}
