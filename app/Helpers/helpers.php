<?php
if (!function_exists('root_path')) {
    function root_path()
    {
        return dirname(__DIR__, 2);
    }
}

if (!function_exists('app_path')) {
    function app_path()
    {
        return dirname(__DIR__, 2) . '/app';
    }
}

if (!function_exists('public_path')) {
    function public_path()
    {
        return dirname(__DIR__, 2) . '/public';
    }
}

if (!function_exists('storage_path')) {
    function storage_path()
    {
        return dirname(__DIR__, 2) . '/storage';
    }
}

if (!function_exists('array_to_path')) {
    function array_to_path(array $pathList): string
    {
        $path = implode(DS, $pathList);
        return $path;
    }
}

if (!function_exists('config')) {
    function config(string $access)
    {
        $access = trim($access, "\.");
        $confArr = explode('.', $access);

        $file = array_shift($confArr);

        $config = require root_path() . "/config/$file.php";
        foreach ($confArr as $confKey) {
            $config = $config[$confKey];
        }

        return $config;
    }
}

if (!function_exists('get_referer')) {
    function get_referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $status = 301, bool $die = true)
    {
        header("Location: $url", true, $status);
        if ($die) {
            die;
        }
    }
}


if (!function_exists('pop')) {
    function pop(array $array)
    {
        return array_slice($array, count($array) - 1, 1)[0];
    }
}

if (!function_exists('check_user_status')) {
    function check_user_status()
    {
        $statusList  = [
            'logged_in' => isset($_SESSION['user']),
            'paid' => false,
        ];

        if ($_SESSION['products']) {
            $flag = false;
            foreach ($_SESSION['products'] as $product) {
                $flag = $product['subscribed'] | $flag;
            }

            $statusList['paid'] = (bool) $flag;
        }

        return $statusList;
    }
}

if (!function_exists('dd')) {
    function dd($value, bool $die = true)
    {
        echo "<pre>";
        var_dump($value);
        echo "</pre>\n===========================\n";
        if ($die) {
            die;
        }
    }
}

if (!function_exists('list_member_method')) {
    function list_member_method($instance, bool $die = true)
    {
        echo '<pre>';
        var_dump(get_class_methods($instance));
        echo '</pre>';
        if ($die) {
            die;
        }
    }
}

if (!function_exists('csrf')) {
    function csrf()
    {
        return strtr(substr(base64_encode(openssl_random_pseudo_bytes(16)), 0, 16), '/+', '_-');
    }
}
