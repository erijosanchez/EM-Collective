<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Crear una orden a partir del carrito y los datos del formulario.
     */
    public function createOrder(array $data): Order
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product', 'items.variant.size', 'items.variant.color', 'coupon']);

        if ($cart->is_empty) {
            throw new \Exception('El carrito está vacío.');
        }

        $summary = $this->cartService->getSummary($cart);

        return DB::transaction(function () use ($cart, $data, $summary) {
            // Crear orden
            $order = Order::create([
                'user_id'              => auth()->id(),
                'coupon_id'            => $cart->coupon_id,
                'guest_email'          => auth()->check() ? null : ($data['email'] ?? null),
                'customer_name'        => $data['first_name'] . ' ' . $data['last_name'],
                'customer_email'       => $data['email'],
                'customer_phone'       => $data['phone'],
                'customer_dni'         => $data['dni'] ?? null,
                'shipping_department'  => $data['department'],
                'shipping_province'    => $data['province'],
                'shipping_district'    => $data['district'],
                'shipping_address'     => $data['address'],
                'shipping_reference'   => $data['reference'] ?? null,
                'subtotal'             => $summary['subtotal'],
                'discount_amount'      => $summary['discount'],
                'shipping_cost'        => $summary['shipping'],
                'total'                => $summary['total'],
                'payment_method'       => $data['payment_method'],
                'payment_status'       => 'pending',
                'status'               => 'pending',
                'notes'                => $data['notes'] ?? null,
            ]);

            // Crear items de la orden con snapshot del producto
            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->variant;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $product->id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name'       => $product->name,
                    'product_sku'        => $variant?->sku ?? $product->sku,
                    'variant_info'       => $variant?->label,
                    'product_image'      => $product->primary_image,
                    'unit_price'         => $cartItem->unit_price,
                    'quantity'           => $cartItem->quantity,
                    'total'              => $cartItem->line_total,
                ]);

                // Reducir stock de la variante
                if ($variant) {
                    $variant->decrement('stock', $cartItem->quantity);
                }
            }

            // Registrar uso del cupón
            if ($cart->coupon_id) {
                CouponUsage::create([
                    'coupon_id' => $cart->coupon_id,
                    'order_id'  => $order->id,
                    'user_id'   => auth()->id(),
                ]);
                Coupon::where('id', $cart->coupon_id)->increment('used_count');
            }

            // Vaciar el carrito
            $this->cartService->clear();

            return $order;
        });
    }
}
