<?php
require_once '../config/dbConfig.php';
require_once '../controller/PostController.php';

header('Content-Type: application/json');

$controller = new PostController();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if ($requestMethod == 'GET' && strpos($_SERVER['REQUEST_URI'], 'postRoutes.php') !== false) {
    $controller->getPosts();
}elseif ($requestMethod == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->createPost($data);
}elseif ($requestMethod == 'PUT' && isset($_GET['id'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller->updatePost($_GET['id'], $data);
}elseif ($requestMethod == 'DELETE' && isset($_GET['id'], $_GET['user_id'])) {
    $controller->deletePost($_GET['id'], $_GET['user_id']);
}else {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
}
?>

