<?php

require_once __DIR__ . '/../config/dbConfig.php';

class CreateUsersTable {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
            avatar VARCHAR(255) DEFAULT 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->conn->exec($sql);
            echo "Table 'users' created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating table 'users': " . $e->getMessage());
        }
    }
}

// Khởi tạo và chạy migration
$db = (new Database())->connect();
$migration = new CreateUsersTable($db);
$migration->up();

?>