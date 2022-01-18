<?php

use App\Controllers\UserController;

require_once __DIR__ . '/../../bootstrap/app.php';

$userController = new UserController();

if ('get' === $request->method()) {
    $userController->verifyEmail();
} elseif ('post' === $request->method()) {
    $response->with(404);
}
