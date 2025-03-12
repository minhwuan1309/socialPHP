<?php
require_once __DIR__ . '/../config/dbConfig.php';

class CreateNotificationsTable {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            type VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        try {
            $this->conn->exec($sql);
            echo "Table 'notifications' created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating table 'notifications': " . $e->getMessage());
        }
    }
}

$db = (new Database())->connect();
$migration = new CreateNotificationsTable($db);
$migration->up();
