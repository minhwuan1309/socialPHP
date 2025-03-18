<?php

require_once __DIR__ . '/../controller/UserController.php';
header('Content-Type: application/json');

$controller = new UserController();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if ($requestMethod == 'GET' && isset($path[count($path) - 1]) && $path[count($path) - 1] == 'users') {
    $controller->getUsers();
}elseif($requestMethod == 'GET' && isset($_GET['id'])){
    $controller->getUser($_GET['id']);
}elseif ($requestMethod == 'POST') { 
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createUser($data);
} elseif ($requestMethod == 'PUT' && isset($_GET['id'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->updateUser($_GET['id'], $data);
}elseif($requestMethod == 'DELETE' && isset($_GET['id'])){
    $controller->deleteUser($_GET['id']);
}else{
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}

?>