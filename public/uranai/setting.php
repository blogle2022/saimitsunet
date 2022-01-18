<?php

use App\Controllers\PartnerController;

require_once __DIR__ . '/../../bootstrap/app.php';

$partnerController = new PartnerController();

if ('get' === $request->method()) {
    echo $partnerController->index($response);
}
