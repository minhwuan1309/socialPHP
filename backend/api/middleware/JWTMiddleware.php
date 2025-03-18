<?php

require_once __DIR__ . '/../helpers/JWTHelper.php';

class JWTMiddleware
{
    public function authenticate(){
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(array("message" => "Unauthorized."));
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = JWTHelper::verifyToken($token);

        if (!$user) {
            http_response_code(401);
            echo json_encode(array("message" => "Unauthorized."));
            exit;
        }

        return $user;
    }
}

?>