<?php

namespace App\Controllers;

use App\Services\Request;
use App\Services\Response;
use App\Services\Model;
use App\Services\Email;
use App\Services\Product;
use App\Services\Url;

class UserController
{
    private $request;
    private $response;
    private $base;
    private $payment;
    private $verifyEmail;
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->base = new Model('base');
        $this->payment = new Model('payments');
        $this->verifyEmail = new Model('verify_email');
        $this->stripe = new \Stripe\StripeClient(config('stripe.api')['secret']);
    }

    public function sendEmail()
    {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            return false;
        }

        $token = strtr(substr(base64_encode(openssl_random_pseudo_bytes(16)), 0, 16), '/+', '_-');
        $emailInBase = $this->base->get('mail', '=', $email);

        $result = $this->verifyEmail->updateOrInsert([
            'email' => $email,
            'token' => $token,
        ], 'email', 'token');

        if ($result && !$emailInBase) {
            $url = new Url();
            $verifyUrl = "$url->root/user/verify.php?token=$token";
            $body = $this->response->view('email.verify-email', ['verifyUrl' => $verifyUrl]);

            $mailer = new Email();
            $mailer->send($email, '細密占星術 仮登録', $body);
            return true;
        } else {
            return false;
        }
    }

    public function verifyEmail()
    {
        $token = $this->request->get('token');
        $verifyData = $this->verifyEmail->get('token', '=', $token);
        $baseTime = strtotime($verifyData['updated_at']);
        $now = time();
        $limitHour = 24;
        $limitSecond = $limitHour * 3600;
        if ($verifyData['token'] !== $token) {
            echo '無効なURLです。5秒後にメール登録ページに遷移します。';
            echo '<script>window.location.href = "/user/signup.php"</script>';
            die;
        }

        if ((($now - $baseTime) / $limitSecond) <= 1) {
            $_SESSION['email'] = $verifyData['email'];
            session_write_close();
            redirect('/user/regist.php');
            exit;
        } else {
            $_SESSION['verifyFailed'] = 'urlFailed';
            session_write_close();
            redirect('/user/signup.php');
            exit;
        };
    }

    public function registForm()
    {
        $params = [
            'email' => $_SESSION['email'],
        ];
        return $this->response->view('regist', $params);
    }

    public function registUser()
    {
        $validationItem = [
            'mail',
            'pass',
            'name',
            'sex',
            'mariage',
            'born',
            'time',
            'in_japan',
            'area',
            'city',
        ];
        $userData = $this->request->post();
        $data = [
            'mail' => $userData['mail'],
            'pass' => password_hash($userData['pass'], PASSWORD_BCRYPT),
            'name' => $userData['name'],
            'nick' => $userData['nick'],
            'sex' => $userData['sex'],
            'mariage' => $userData['mariage'],
            'born' => $userData['born'],
            'time' => $userData['time'],
            'is_foreign' => $userData['foreign'],
            'area' => $userData['area'],
            'city' => $userData['city'],
            'bwhere' => $userData['area'] . $userData['city'],
        ];
        $result = $this->base->addSingle($data);

        return $result;
    }

    public function login()
    {
        $userData = $this->request->post();
        $record = $this->base->get('mail', '=', $userData['mail']);
        $password = $record['pass'];
        $loginTest = password_verify($userData['pass'], $password);

        if ($loginTest) {
            unset($record['pass']);
            $_SESSION['loginFailed'] = false;
            $_SESSION['user'] = $record;

            $payment = pop($this->payment->find('email', '=', $record['mail']));
            $_SESSION['stripe_customer'] = $payment['customer_id'];


            $product = new Product(config('stripe.api.secret'));
            $products = $product->getProducts($_SESSION['stripe_customer']);

            $_SESSION['products'] = $products;
            redirect('/uranai');
        } else {
            $_SESSION['loginFailed'] = true;
            session_write_close();
            redirect('/user/login.php');
            die;
        }
    }

    public function update(array $userData)
    {
        $data = [
            'mail' => $userData['mail'],
            'pass' => password_hash($userData['pass'], PASSWORD_BCRYPT),
            'name' => $userData['name'],
            'nick' => $userData['nick'],
            'sex' => $userData['sex'],
            'mariage' => $userData['mariage'],
            'born' => $userData['born'],
            'time' => $userData['time'],
            'is_foreign' => $userData['foreign'],
            'area' => $userData['area'],
            'city' => $userData['city'],
            'bwhere' => $userData['area'] . $userData['city'],
        ];
        $result = $this->base->updateOrInsert($data, 'mail');
        $_SESSION['updated'] = $result;
        if ($result) {
            $_SESSION['user'] = $data;
            $_SESSION['user']['pass'] = $userData['pass'];
        }

        redirect('/user/edit.php');
    }

    public function plans(Response $response)
    {
        $token = md5(time() . rand(1000, 9999));
        $_SESSION['token'] = $token;
        $params = $_SESSION;

        $payment = $this->payment->get('email', '=', $_SESSION['user']['mail']);
        $_SESSION['stripe_customer'] = $payment['customer_id'];
        return $response->view('plan', $params);
    }
}
