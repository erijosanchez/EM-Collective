<?php

return [
    'public_key'   => env('MP_PUBLIC_KEY', ''),
    'access_token' => env('MP_ACCESS_TOKEN', ''),
    'sandbox'      => env('MP_SANDBOX', true),
    'currency'     => 'PEN',
    'base_url'     => 'https://api.mercadopago.com',
    'webhook_url'  => env('APP_URL', 'http://localhost') . '/webhook/mercadopago',
];
