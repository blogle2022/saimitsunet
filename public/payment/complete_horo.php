<?php

use App\Services\Model;
use App\Services\Product;

require_once __DIR__ . '/../../bootstrap/app.php';

$stripe = new \Stripe\StripeClient(
    config('stripe.api')['secret'],
);
$cs = $stripe->checkout->sessions->retrieve(
    $request->get('session_id'),
    []
);

if (!isset($_SESSION['stripe_customer'])) {
    $_SESSION['stripe_customer'] = $cs['customer'];
}

$_SESSION['payment'] = isset($_SESSION['stripe_customer']);
redirect('/uranai/horo.php');
