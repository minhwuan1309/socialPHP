<?php

class User{
    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllUSers(){
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getUserById($id){
        $query = "SELECT * FROM " . $this->table . "WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
}

?>