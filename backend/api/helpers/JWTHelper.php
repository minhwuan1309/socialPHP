<?php

require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper {
    public static function createToken($payload) {
        return JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');
    }

    public static function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>