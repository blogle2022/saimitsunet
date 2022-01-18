<?php

use App\Controllers\PartnerController;

require_once __DIR__ . '/../../bootstrap/app.php';

if ('post' === $request->method()) {
    $partnerController = new PartnerController();
    $partnerController->delete($request);
}
