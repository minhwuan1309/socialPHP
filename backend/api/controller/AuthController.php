<?php

require_once __DIR__ . '/../config/dbConfig.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/JWTHelper.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validateRegisterData($data)) return;

        $user = new User($this->db);
        $result = $user->createUser($data['name'], $data['email'], $data['password'], $data['avatar'] ?? null);

        if ($result === "exists") {
            http_response_code(409);
            echo json_encode(["message" => "Email already exists."]);
        } elseif ($result) {
            http_response_code(201);
            echo json_encode(["message" => "User created successfully.", "user_id" => $result]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to create user."]);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validateLoginData($data)) return;

        $user = new User($this->db);
        $user_data = $user->getUserByEmail($data['email']);

        if ($user_data && password_verify($data['password'], $user_data['password'])) {
            $token = JWTHelper::createToken([
                "id" => $user_data["id"],
                "email" => $user_data["email"],
                "name" => $user_data["name"],
                "avatar" => $user_data["avatar"],
                "role" => $user_data["role"]
            ]);

            http_response_code(200);
            echo json_encode(["message" => "Login successful.", "token" => $token]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid credentials."]);
        }
    }

    public function resetPassword() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validateResetPasswordData($data)) return;

        $user = new User($this->db);
        $existingUser = $user->getUserByEmail($data['email']);

        if (!$existingUser) {
            http_response_code(404);
            echo json_encode(["message" => "User not found."]);
            return;
        }

        if ($user->resetPassword($data['email'], $data['newPassword'])) {
            http_response_code(200);
            echo json_encode(["message" => "Password reset successful."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to reset password."]);
        }
    }

    private function validateRegisterData($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields."]);
            return false;
        }
        return true;
    }

    private function validateLoginData($data) {
        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields."]);
            return false;
        }
        return true;
    }

    private function validateResetPasswordData($data) {
        if (empty($data['email']) || empty($data['newPassword'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields."]);
            return false;
        }
        return true;
    }
}

?>
