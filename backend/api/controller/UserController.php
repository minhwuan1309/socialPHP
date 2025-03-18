<?php

require_once '../config/dbConfig.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function getUsers(){
        $result = $this->user->getAllUSers();
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }
}

$controller = new UserController();
if($_SERVER['REQUEST_METHOD' ] == 'POST') {
    $controller->getUsers();
}

?>