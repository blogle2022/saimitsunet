<?php

use App\Controllers\PartnerController;

require_once __DIR__ . '/../../bootstrap/app.php';

$partnerController = new PartnerController();

if ('get' === $request->method()) {
    $response->with(404);
} elseif ('post' === $request->method()) {
    $partnerController->regist($request);
}
