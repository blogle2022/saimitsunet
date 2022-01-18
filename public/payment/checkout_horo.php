<?php
require_once __DIR__ . '/../../bootstrap/app.php';

if ('get' === $request->method()) {
    $response->with(404);
} elseif ('post' === $request->method()) {
    if ($request->post('_token') !== $_SESSION['token']) {
        redirect('/uranai/horo.php');
    }

    // Set your secret key. Remember to switch to your live secret key in production.
    // See your keys here: https://dashboard.stripe.com/apikeys
    \Stripe\Stripe::setApiKey(config('stripe.api.secret'));
    $URL = config('app.protocol') . '://' . config('app.host');
    // The price ID passed from the front end.
    //   $priceId = $_POST['priceId'];
    $priceId = $_ENV['stripe_horoscope_price'];

    $checkoutParams = [
        'success_url' => "$URL/payment/complete_horo.php?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url' => "$URL/uranai/horo.php?checkout=false",
        'payment_method_types' => ['card'],
        'allow_promotion_codes' => true,
        'mode' => 'payment',
        'line_items' => [[
            'price' => $priceId,
            // For metered billing, do not pass quantity
            'quantity' => 1,
        ]],
    ];

    if (isset($_SESSION['stripe_customer'])) {
        $checkoutParams['customer'] = $_SESSION['stripe_customer'];
    } else {
        $checkoutParams['customer_email'] = $_SESSION['user']['mail'];
    }

    $checkoutParams['metadata'] = [
        'product' => 'horoscope',
    ];

    $session = \Stripe\Checkout\Session::create($checkoutParams);

    // Redirect to the URL returned on the Checkout Session.
    // With vanilla PHP, you can redirect with:
    redirect($session->url, 303);
}
