<?php

use App\Controllers\UserController;

require_once __DIR__ . '/../../bootstrap/app.php';

/*
if (!$_SESSION['user']) {
    redirect('/user/login.php');
}
*/
if ('get' === $request->method()) {
    $params = $_SESSION;
    $_SESSION['editToken'] = md5($_SESSION['user']['id']);
    $params['_token'] = $_SESSION['editToken'];
    $params['userJson'] = json_encode($_SESSION['user'], JSON_UNESCAPED_UNICODE);
    echo $response->view('edit', $params);
    $_SESSION['updated'] = null;
} elseif ('post' === $request->method()) {
    if ($request->post('_token') !== $_SESSION['editToken']) {
        redirect('/user/edit.php');
    }

    $userController = new UserController();
    $userController->update($request->post());
}
