<?php

return [

    'hash_alphabet' => env(
        'STRIPE_IDS_HASH_ALPHABET',
        '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    ),

    'hash_length' => env('STRIPE_IDS_HASH_LENGTH', 16),

    'generator' => \Mitchdav\StripeIds\Generators\TimestampFirstGenerator::class,

];