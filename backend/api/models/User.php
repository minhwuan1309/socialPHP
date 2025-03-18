<?php

class User{
    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllUsers(){
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getUserById($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    public function createUser($name, $email, $password, $avatar) {
        $query = "INSERT INTO " . $this->table . " (name, email, password, avatar) 
                  VALUES (:name, :email, :password, :avatar)";
        $stmt = $this->conn->prepare($query);

        // Hash mật khẩu trước khi lưu
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":avatar", $avatar);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Trả về ID user vừa tạo
        } else {
            return false; // Trả về false nếu có lỗi
        }
    }

    // ✅ UPDATE - Cập nhật thông tin người dùng
    public function updateUser($id, $data) {
        $query = "SELECT name, email, avatar FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingUser) {
            return false;
        }

        $name = isset($data['name']) ? $data['name'] : $existingUser['name'];
        $email = isset($data['email']) ? $data['email'] : $existingUser['email'];
        $avatar = isset($data['avatar']) ? $data['avatar'] : $existingUser['avatar'];

        // Cập nhật user
        $updateQuery = "UPDATE " . $this->table . " SET name = :name, email = :email, avatar = :avatar WHERE id = :id";
        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bindParam(":id", $id, PDO::PARAM_INT);
        $updateStmt->bindParam(":name", $name);
        $updateStmt->bindParam(":email", $email);
        $updateStmt->bindParam(":avatar", $avatar);


        return $updateStmt->execute();
    }

    public function deleteUser($id){
        $query = " DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
    
        return $stmt->execute();
    }
}

?>