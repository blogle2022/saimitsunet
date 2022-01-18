<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/app.php';

$payment = new Model('payments');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = config('stripe.api')['webhook_secret'];

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $invoice = $event->data->object;
        if ('horoscope' === $invoice->metadata->product) {
            $horoscope = new Model('horoscope');
            $data = [
                'base_email' => $invoice->customer_details->email,
                'horoscope' => $invoice->payment_intent,
            ];

            $result = $horoscope->addSingle($data);

            if (!$result) {
                $response->with(400);
            }
        };
        break;
    case 'invoice.paid':
        $invoice = $event->data->object;
        $invoiceData = [
            'customer_id' => $invoice['customer'],
            'email' => $invoice['customer_email'],
        ];
        $results = $payment->addSingle($invoiceData);

        if (!$results) {
            echo $invoiceData;
            $response->with(400);
        }
        break;
    case 'invoice.payment_failed':
        $invoice = $event->data->object;
        break;
        // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
