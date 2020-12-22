<?php

return [

    'hash_alphabet' => env(
        'STRIPE_IDS_HASH_ALPHABET',
        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ),

    'hash_length' => env('STRIPE_IDS_HASH_LENGTH', 16),

    // The 'prefixes' key is optional, and is only required if you are using the StripeIds::findByStripeId() method to
    // find generic models by their id.

    'prefixes' => [

        // 'ch_' => \App\Models\Charge::class,

    ],

];