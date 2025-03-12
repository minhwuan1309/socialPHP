<?php
require_once __DIR__ . '/../config/dbConfig.php';

class CreatePostsTable {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        try {
            $this->conn->exec($sql);
            echo "Table 'posts' created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating table 'posts': " . $e->getMessage());
        }
    }
}

$db = (new Database())->connect();
$migration = new CreatePostsTable($db);
$migration->up();

?>