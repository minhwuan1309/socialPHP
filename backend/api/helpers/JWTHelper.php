<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Đảm bảo đường dẫn đúng

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Đổi đường dẫn về thư mục gốc
$dotenv->safeLoad(); // Dùng safeLoad() để tránh lỗi nếu .env không tồn tại

class JWTHelper {
    private static $secret_key;

    public static function init() {
        self::$secret_key = $_ENV['JWT_SECRET'] ?? "default_secret_key";
    }

    public static function createToken($payload) {
        return JWT::encode($payload, self::$secret_key, 'HS256');
    }

    public static function verifyToken($token) {
        try {
            return JWT::decode($token, new Key(self::$secret_key, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}

JWTHelper::init();

?>