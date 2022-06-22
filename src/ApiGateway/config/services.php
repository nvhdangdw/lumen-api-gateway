<?php

declare(strict_types = 1);

return [
    'products' => [
        'base_uri' => env('PRODUCTS_SERVICE_BASE_URI'),
        'secret' => env('PRODUCTS_SERVICE_SECRET')
    ],
    'orders' => [
        'base_uri' => env('ORDERS_SERVICE_BASE_URI'),
        'secret' => env('ORDERS_SERVICE_SECRET'),
    ]
];
