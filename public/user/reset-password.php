<?php

use App\Services\Email;
use App\Services\Model;
use App\Services\Url;

require_once __DIR__ . '/../../bootstrap/app.php';

$resetPassword = new Model('reset_password');
$base = new Model('base');

if ($userStatus['logged_in']) {
    redirect('/');
}

if ('get' === $request->method()) {
    if ($request->get('token')) {
        $token = $request->get('token');
        $params = [
            '_token' => $token,
        ];

        echo $response->view('password.reset-password', $params);
        exit;
    } else {
        $params = [
            '_token' => md5(time() . rand(1000, 9999)),
        ];

        echo $response->view('password.send-email', $params);
        exit;
    }
} elseif ('post' === $request->method()) {
    if ($request->post('email')) {
        $baseRecord = pop($base->find('mail', '=', $request->post('email')));
        if (!$baseRecord['mail']) {
            $_SESSION['email_not_exists'] = true;
            redirect('/user/reset-password');
        }
        $url = new Url();
        $rootUrl = $url->root();
        $resetUri = config('app.urls.reset_password');
        $token = md5($request->post('email') . rand(1000, 9999));
        $emailAddress = $request->post('email');
        $resetUrl = "$rootUrl$resetUri?token=$token";

        $email = new Email();
        $body = "下記のリンクから新しいパスワードを設定してください。\n$resetUrl";

        $currentTime = time();
        $limit = 24 * 60 * 60;
        $limitTime = $currentTime + $limit;

        $resetPassword->addSingle([
            'email' => $request->post('email'),
            'token' => $token,
            'expiration' => $limitTime,
        ]);

        $email->send($emailAddress, 'パスワードの変更 | 細密占星術', $body);

        echo 'メール本文内のリンクからパスワードを再設定して下さい。<br><a href="/">トップ</a>に戻る';
        exit;
    } elseif ($request->post('password')) {
        $record = pop($resetPassword->find('token', '=', $request->post('_token')));
        $now = time();

        if ($record['expiration'] < $now) {
            $_SESSION['reset_timeout'] = true;
            redirect('/');
        }
        $email = $record['email'];
        $password = password_hash($request->post('password'), PASSWORD_BCRYPT);
        $baseRecord = $base->get('mail', '=', $email);
        $baseRecord['pass'] = $password;
        $update = [
            'mail' => $email,
            'pass' => $password,
        ];
        unset($baseRecord['id']);
        $result = $base->updateOrInsert($baseRecord, 'mail');

        $_SESSION['reset_password'] = $result;
        redirect('/user/login.php');
        die;
    }
}
