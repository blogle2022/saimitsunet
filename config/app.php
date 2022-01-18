<?php

return [
    'protocol' => $_SERVER['HTTPS'] ? 'https' : 'http',
    'host' => trim($_SERVER['HTTP_HOST'], '\/'),
    'urls' => [
        'login' => '/user/login.php',
        'regist_user' => '/user/regist',
        'reset_password' => '/user/reset-password.php',
        'change_email' => '/user/change-email.php',
        'images' => '/resources/images',
    ],
];
