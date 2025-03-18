<?php

require_once __DIR__ . '/../controller/UserController.php';
header('Content-Type: application/json');

$controller = new UserController();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if ($requestMethod == 'GET' && isset($path[count($path) - 1]) && $path[count($path) - 1] == 'users') {
    $controller->getUsers();
} else {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
}

?>