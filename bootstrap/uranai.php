<?php
require_once __DIR__ . '/app.php';

include(public_path() . "/libs/pageopen.php");
include(public_path() . "/libs/payment_needed.php");
include(public_path() . "/libs/astro.php");
include(public_path() . "/libs/horo.php");

if (!check_user_status()['logged_in']) {
    redirect('/user/login.php');
}
