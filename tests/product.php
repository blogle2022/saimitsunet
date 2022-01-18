<?php

use App\Services\Product;

require_once __DIR__ . '/../bootstrap/app.php';

$product = new Product(config('stripe.api.secret'));

$productList = $product->makeList('');

var_dump($productList);
