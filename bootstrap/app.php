<?php

use Dotenv\Dotenv;
use App\Services\Request;
use App\Services\Response;

if (PHP_SESSION_NONE === session_status()) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Tokyo');
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = new Request();

$response = new Response();

$userStatus = check_user_status();
