<?php

require_once __DIR__ . '/../../bootstrap/app.php';

session_start();
$_SESSION = [];
session_destroy();
redirect('/user/login.php');
die;
