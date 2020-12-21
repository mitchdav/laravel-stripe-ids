<?php

return [

    'alphabet' => env('STRIPE_IDS_ALPHABET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),

    'length' => env('STRIPE_IDS_LENGTH', 16),

    'separator' => env('STRIPE_IDS_SEPARATOR', '_'),

    // The 'prefixes' key is optional, and is only required if you are using the StripeIds::findByStripeId() method to
    // find generic models by their id.

    'prefixes' => [

        // 'ch' => \App\Models\Charge::class,

    ],

];