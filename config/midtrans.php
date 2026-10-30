<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-LapangankitaSandboxDemoKey123'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-LapangankitaSandboxClientDemo123'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'G123456789'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => true,
    'is_3ds' => true,
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];
