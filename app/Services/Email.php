<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    public static function send(string $recipient, string $subject, string $body)
    {
        $mailer = new PHPMailer();
        try {
            //Server settings
            $mailer->CharSet = $_ENV['mail_charset'];
            # $mailer->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mailer->isSMTP();                                            //Send using SMTP
            $mailer->Host       = $_ENV['mail_host'];                     //Set the SMTP server to send through
            $mailer->SMTPAuth   = $_ENV['smtp_auth'];                                   //Enable SMTP authentication
            $mailer->Username   = $_ENV['mail_account'];                     //SMTP username
            $mailer->Password   = $_ENV['mail_password'];                               //SMTP password
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mailer->Port       = $_ENV['smtp_port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mailer->setFrom($_ENV['mail_from'], $_ENV['mail_from_name']);
            $mailer->addAddress($recipient);     //Add a recipient

            //Content
            $mailer->isHTML(false);                                  //Set email format to HTML
            $mailer->Subject = $subject;
            $mailer->Body    = $body;

            $mailer->send();
            return true;
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}
