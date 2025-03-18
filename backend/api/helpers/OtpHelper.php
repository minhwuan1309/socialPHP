<?php

class OtpHelper{

    public static function generateOtp(){
        return rand(100000, 999999);
    }

    public static function storeOtp($email, $otp){
        $_SESSION['otp'][$email] = [
            'otp' => $otp,
            'expires' => time() + 300 //expires in 5 minutes
        ];
    }

    public static function verifyOtp($email, $otp){
        if(!isset($_SESSION['otp'][$email])) return false;

        $storedOtp = $_SESSION['otp'][$email]['otp'];

        if($storedOtp['otp'] == $otp && $storedOtp['expires'] > time()) {
            unset($_SESSION['otp'][$email]);
            return true;
        }

        return false;
    }
}

?>