<?php

use App\Controllers\UserController;

require_once __DIR__ . '/../../bootstrap/app.php';

if (check_user_status()['logged_in']) {
    redirect('/uranai');
}

if ('get' === $request->method()) {
    $params = [
        'isLoggedIn' => $isLoggedIn,
        'nick' => $nick,
    ];

    $params['loginFailed'] = isset($_SESSION['loginFailed']);
    echo $response->view('login', $params);
} elseif ('post' === $request->method()) {
    $userController = new UserController();
    $userController->login();
}
