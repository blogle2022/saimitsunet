<?php

namespace App\Services;

class Request
{
    public function get(string $key = null)
    {
        if (!isset($_GET)) return  null;

        if (!$key) {
            return $_GET;
        } else {
            return $_GET[$key];
        }
    }

    public function post(string $key = null)
    {
        if (!isset($_POST)) return false;

        if (!$key) {
            return $_POST;
        } else {
            return $_POST[$key];
        }
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
