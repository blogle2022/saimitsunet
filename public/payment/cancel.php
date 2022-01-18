<?php

require_once __DIR__ . '/../../bootstrap/app.php';
$URL = config('app.protocol') . '://' . config('app.host');
if ('post' === $request->method()) {
    $stripe = new \Stripe\StripeClient(
        config('stripe.api.secret')
    );

    $customerHash = md5($_SESSION['stripe_customer']);
    $_SESSION['customer_hash'] = $customerHash;
    $session = $stripe->billingPortal->sessions->create([
        'customer' => $_SESSION['stripe_customer'],
        'return_url' => "$URL/uranai/?check=$customerHash",
    ]);

    redirect($session->url, 303);
}
