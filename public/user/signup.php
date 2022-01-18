<?php

use App\Controllers\UserController;

require_once __DIR__ . '/../../bootstrap/app.php';

$userController = new UserController();
if ('get' === $request->method()) {
    echo $response->view('signup', $_SESSION);
} elseif ('post' === $request->method()) {
    $result = $userController->sendEmail();
    if ($result) {
        redirect('/user/mailsent.php');
    } else {
        $_SESSION['verifyFailed'] = 'emailExists';
        redirect('/user/signup.php');
    }
}
