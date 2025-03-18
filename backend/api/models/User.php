<?php

class User{
    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllUsers(){
        $query = "SELECT id, name, email, avatar, role FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getUser($search){
        $query = "SELECT id, name, email, avatar, role FROM " . $this->table . " WHERE email LIKE :search OR name LIKE :search";
        $stmt = $this->conn->prepare($query);
        $search = "%" . $search . "%"; // Tìm kiếm gần đúng
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

    public function createUser($name, $email, $password, $avatar) {
        if($this->getUserByEmail($email)){
            return "exists";
        }
        

        $query = "INSERT INTO " . $this->table . " (name, email, password, avatar, role) 
                  VALUES (:name, :email, :password, :avatar, 'user')";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":avatar", $avatar);


        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); 
        } else {
            return false; 
        }
    }

    public function updateUser($id, $data) {
        $existingUser = $this->getUserByEmail($data['email'] ?? '');
        if ($existingUser && $existingUser['id'] != $id) {
            return "exists";
        }

        $query = "UPDATE " . $this->table . " SET 
                  name = COALESCE(:name, name), 
                  email = COALESCE(:email, email), 
                  avatar = COALESCE(:avatar, avatar) 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $data["name"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":avatar", $data["avatar"]);

        return $stmt->execute();
    }

    public function deleteUser($id){
        $query = " DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
    
        return $stmt->execute();
    }

    //Auth
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($email, $newPassword) {
        $hashed_password = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE " . $this->table . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }   
}
?>