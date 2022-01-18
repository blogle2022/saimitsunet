<?php

use Stripe\Subscription;

require_once __DIR__ . '/../bootstrap/app.php';
$stripe = new \Stripe\StripeClient("sk_test_51J2VLXDcIsNrsZEOkVdvmYFlkl2s4lSpOKOApAG7tvQKNvDP0cJUBJS5Bz9DOqgyaVlcs2pZ6YUvZAuhEWqW63Xh005h1SByuh");

$subscriptions = $stripe->subscriptions->all([
    'customer' => 'cus_KH0bHlxlhkAVBL',
]);

dd($subscriptions);
