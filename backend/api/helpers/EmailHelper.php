<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . "/../../.env")) {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv->load();
}

class EmailHelper {
    public static function sendEmail($toEmail, $otp) {
        $email = new PHPMailer(true);

        try{
            $email->isSMTP();
            $email->Host = 'smtp.gmail.com';
            $email->SMTPAuth = true;
            $email->Username = $_ENV['EMAIL_EMAIL'];
            $email->Password = $_ENV['EMAIL_PASSWORD'];
            $email->SMTPSecure = 'PHPMailer::ENCRYPTION_STARTTLS';
            $email->Port = 587;

            $email->setFrom($_ENV['EMAIL_EMAIL'], 'Social Network');
            $email->addAddress($toEmail);
            $email->isHTML(true);
            $email->Subject = 'Your OTP Code';
            $email->Body    = "Your OTP code is: $otp. It will expire in 5 minutes.";

            $email->send();
            return true;
        }
        catch(Exception $e) {
            return false;
        }
    }
}

?>