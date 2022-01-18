<?php

return [
    'api' => [
        'secret' => $_ENV['stripe_api_secret'],
        'webhook_secret' => $_ENV['stripe_webhook_secret'],
    ],
    'products' => [
        'premium' => 'prod_KbHAYcaXYpdrF0',
        'horoscope' => 'prod_KdSxYiec9CPxd5',
    ],
    'price' => [
        'subscription' => $_ENV['stripe_subscription_price'],
    ]
];
