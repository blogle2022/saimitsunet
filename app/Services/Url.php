<?php

namespace App\Services;

class Url
{
    public $root;

    function __construct()
    {
        $baseUrl = config('app.protocol') . '://' . config('app.host');

        $this->root = $baseUrl;
    }

    public function root()
    {
        return $this->root;
    }
}
