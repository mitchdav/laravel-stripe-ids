<?php

return [

    'alphabet' => env('STRIPE_IDS_ALPHABET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),

    'length' => env('STRIPE_IDS_LENGTH', 16),

    'separator' => env('STRIPE_IDS_SEPARATOR', '_'),

];