<?php

namespace App\Services;

class Response
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(root_path() . '/resources/views');
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addGlobal('user_status', check_user_status());
        $this->twig->addGlobal('user', $_SESSION['user']);
        $this->twig->addGlobal('urls', config('app.urls'));
        $this->twig->addGlobal('product_design', config('design'));
        $this->twig->addGlobal('products', $_SESSION['products']);
        $this->twig->addGlobal('uranaiList', config('products'));
    }

    public function view(string $template, array $params = [], $extension = 'html')
    {
        $templatePath = str_replace('.', DIRECTORY_SEPARATOR, $template);
        $ext = str_replace('.', '', $extension);
        $twig = $this->twig->render("$templatePath.$ext", $params);

        return $twig;
    }

    public function with(int $status = 200)
    {
        header('', true, $status);
    }
}
