<?php

header("Content-Type: application/json");

require_once("../controller/AuthController.php");

$controller = new AuthController();

$requestMethod = $_SERVER["REQUEST_METHOD"];
$action = $_GET['action'] ?? '';

if ($requestMethod === 'POST') {
    match ($action) {
        'register' => $controller->register(),
        'login' => $controller->login(),
        'resetPassword' => $controller->resetPassword(),
        default => response(400, "Invalid action")
    };
} else {
    response(405, "Method Not Allowed");
}

function response($code, $message) {
    http_response_code($code);
    echo json_encode(["message" => $message]);
}

?>
