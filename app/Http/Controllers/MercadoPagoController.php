<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
    public function __construct(protected MercadoPagoService $mercadoPagoService) {}

    public function webhook(Request $request)
    {
        Log::info('MercadoPago webhook received', $request->all());

        try {
            $this->mercadoPagoService->processWebhook($request->all());
        } catch (\Exception $e) {
            Log::error('MercadoPago webhook processing error: ' . $e->getMessage());
        }

        return response('OK', 200);
    }
}
