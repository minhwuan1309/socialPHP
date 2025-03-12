<?php

require_once __DIR__ . '/../config/dbConfig.php';
class CreateMessagesTable {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            file_url VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        try {
            $this->conn->exec($sql);
            echo "Table 'messages' created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating table 'messages': " . $e->getMessage());
        }
    }
}

// Khởi tạo và chạy migration
$db = (new Database())->connect();
$migration = new CreateMessagesTable($db);
$migration->up();
?>