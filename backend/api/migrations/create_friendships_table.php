<?php
require_once __DIR__ . '/../config/dbConfig.php';

class CreateFriendshipsTable {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS friendships (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            friend_id INT NOT NULL,
            status ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        try {
            $this->conn->exec($sql);
            echo "Table 'friendships' created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating table 'friendships': " . $e->getMessage());
        }
    }
}

$db = (new Database())->connect();
$migration = new CreateFriendshipsTable($db);
$migration->up();
