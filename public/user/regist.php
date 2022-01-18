<?php

use App\Controllers\UserController;

require_once __DIR__ . '/../../bootstrap/app.php';

$userController = new UserController();

if ('get' === $request->method()) {
    echo $userController->registForm();
    die;
} elseif ('post' === $request->method()) {
    $status = $userController->registUser();
    if (!$status) {
        $_SESSION['registFailed'] = true;
        redirect('/user/regist.php');
    }

    redirect('/user/login.php');
}
