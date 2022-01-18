<?php
require_once __DIR__ . '/../../bootstrap/app.php';

if ('get' === $request->method()) {
    $response->with(404);
} elseif ('post' === $request->method()) {
    if ($request->post('_token') !== $_SESSION['token']) {
        redirect('/uranai');
    }

    // Set your secret key. Remember to switch to your live secret key in production.
    // See your keys here: https://dashboard.stripe.com/apikeys
    \Stripe\Stripe::setApiKey(config('stripe.api.secret'));
    $URL = config('app.protocol') . '://' . config('app.host');
    if ('cancel' === $request->post('cancel')) {
        // Authenticate your user.
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $_SESSION['stripe_customer'],
            'return_url' => "$URL/payment/complete.php?session_id={CHECKOUT_SESSION_ID}",
        ]);

        // Redirect to the customer portal.
        redirect($session->url, 303);
    } else {

        // The price ID passed from the front end.
        //   $priceId = $_POST['priceId'];
        $priceId = config('stripe.price.subscription');

        $checkoutParams = [
            'success_url' => "$URL/payment/complete.php?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => "$URL/uranai/?checkout=false",
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
        ];

        $checkoutParams['mode'] = 'subscription';
        $checkoutParams['allow_promotion_codes'] = true;

        if (isset($_SESSION['stripe_customer'])) {
            $checkoutParams['customer'] = $_SESSION['stripe_customer'];
        } else {
            $checkoutParams['customer_email'] = $_SESSION['user']['mail'];
        }

        $checkoutParams['metadata'] = ['product' => $request->post('product')];

        $session = \Stripe\Checkout\Session::create($checkoutParams);

        // Redirect to the URL returned on the Checkout Session.
        // With vanilla PHP, you can redirect with:
        redirect($session->url, 303);
    }
}
