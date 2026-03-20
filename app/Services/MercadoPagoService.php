<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    protected string $baseUrl;
    protected string $accessToken;

    public function __construct()
    {
        $this->accessToken = config('mercadopago.access_token', '');
        $this->baseUrl     = config('mercadopago.base_url', 'https://api.mercadopago.com');
    }

    // ─── Crear preferencia de pago ────────────────────────────────────────

    public function createPreference(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'          => (string) $item->product_id,
                'title'       => $item->product_name . ($item->variant_label ? ' — ' . $item->variant_label : ''),
                'quantity'    => $item->quantity,
                'unit_price'  => (float) $item->unit_price,
                'currency_id' => 'PEN',
            ];
        }

        // Agregar envío como ítem si aplica
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id'         => 'shipping',
                'title'      => 'Costo de envío',
                'quantity'   => 1,
                'unit_price' => (float) $order->shipping_cost,
                'currency_id' => 'PEN',
            ];
        }

        $payload = [
            'items'              => $items,
            'payer'              => [
                'name'  => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => ['number' => $order->customer_phone],
            ],
            'external_reference' => $order->order_number,
            'back_urls'          => [
                'success' => route('checkout.success', $order),
                'failure' => route('checkout.failed', $order),
                'pending' => route('checkout.pending', $order),
            ],
            'auto_return'        => 'approved',
            'notification_url'   => config('mercadopago.webhook_url'),
            'statement_descriptor' => config('app.name', 'EM Collective'),
        ];

        $response = Http::withToken($this->accessToken)
            ->post("{$this->baseUrl}/checkout/preferences", $payload);

        if ($response->failed()) {
            Log::error('MercadoPago createPreference error', [
                'order'    => $order->order_number,
                'response' => $response->json(),
            ]);
            throw new \Exception('Error al crear preferencia de pago en Mercado Pago.');
        }

        return $response->json();
    }

    // ─── Verificar pago por payment_id ───────────────────────────────────

    public function getPayment(string $paymentId): array
    {
        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/v1/payments/{$paymentId}");

        if ($response->failed()) {
            throw new \Exception('No se pudo obtener información del pago.');
        }

        return $response->json();
    }

    // ─── Marcar orden como pagada ─────────────────────────────────────────

    public function markOrderPaid(Order $order, string $paymentReference): void
    {
        $order->update([
            'payment_status'    => 'paid',
            'payment_reference' => $paymentReference,
            'status'            => 'confirmed',
        ]);
    }

    // ─── Marcar orden como fallida ────────────────────────────────────────

    public function markOrderFailed(Order $order): void
    {
        $order->update([
            'payment_status' => 'failed',
            'status'         => 'cancelled',
        ]);
    }

    // ─── Procesar webhook ─────────────────────────────────────────────────

    public function processWebhook(array $data): void
    {
        $type = $data['type'] ?? $data['topic'] ?? null;

        if ($type !== 'payment') {
            return;
        }

        $paymentId = $data['data']['id'] ?? $data['id'] ?? null;
        if (!$paymentId) return;

        try {
            $payment = $this->getPayment((string) $paymentId);

            $orderNumber = $payment['external_reference'] ?? null;
            if (!$orderNumber) return;

            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) return;

            $status = $payment['status'] ?? 'pending';

            match ($status) {
                'approved' => $this->markOrderPaid($order, (string) $paymentId),
                'rejected', 'cancelled' => $this->markOrderFailed($order),
                default => null,
            };
        } catch (\Exception $e) {
            Log::error('MercadoPago webhook error', ['error' => $e->getMessage(), 'data' => $data]);
        }
    }
}
