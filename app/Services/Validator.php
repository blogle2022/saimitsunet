<?php

namespace App\Services;

class Validator
{
    public $post;

    public function __construct(array $post)
    {
        $this->post = $post;
    }

    public function rule(string $type, string|array $name, string $rule)
    {
        # code...
    }
}
