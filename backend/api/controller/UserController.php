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
        $result = $this->user->getAllUsers();
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }

    public function getUser($id){
        $result = $this->user->getUserById($id);
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user){
            echo json_encode($user);
        }else{
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
        }
    }

    public function createUser($data) {
        if (isset($data['name'], $data['email'], $data['password'])) {
            $avatar = isset($data['avatar']) ? $data['avatar'] : null;
            $userId = $this->user->createUser($data['name'], $data['email'], $data['password'], $avatar);

            if ($userId) {
                http_response_code(201);
                echo json_encode(["message" => "User created successfully", "id" => $userId]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to create user"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
        }
    }

    // ✅ UPDATE - API cập nhật thông tin người dùng
    public function updateUser($id, $data) {
        if ($this->user->updateUser($id, $data)) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User not found or update failed"]);
        }
    }

    public function deleteUser($id){
        if($this->user->deleteUser($id)){
            echo json_encode(['message'=> 'User deleted successfully']);
        }else{
            http_response_code(400);
            echo json_encode(['message'=> 'Failed to delete user']);
        }
    }
}

?>