<?php

use App\Controllers\UserController;
use App\Services\Product;

require_once __DIR__ . '/../../bootstrap/app.php';

if (!$_SESSION['user']) {
    redirect('/user/login.php');
}
$userController = new UserController();
if ('get' === $request->method()) {
    if ($request->get('check')) {
        if ($request->get('check') === md5($_SESSION['stripe_customer'])) {
            $product = new Product(config('stripe.api.secret'));
            $_SESSION['products'] = $product->getProducts($_SESSION['stripe_customer']);
            $here = preg_replace("/\?.+?$/", "", $_SERVER['REQUEST_URI']);
            redirect($here);
        }
    }
    echo $userController->plans($response);
} elseif ('post' === $request->method()) {
    # code...
} else {
    # code...
}
