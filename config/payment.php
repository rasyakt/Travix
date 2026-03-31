<?php

return [
    // Supported providers: dummy, midtrans
    'provider' => env('PAYMENT_PROVIDER', 'dummy'),

    // If true in dummy mode, payment will be auto-approved for development/testing.
    'dummy_auto_approve' => (bool) env('PAYMENT_DUMMY_AUTO_APPROVE', true),
];
