<?php

use App\Services\Request;
use App\Services\Response;

require_once __DIR__ . '/../../bootstrap/app.php';

$requestMethod = $request->method();

if ($requestMethod === 'get') {
    echo $response->view('mailsent');
}
