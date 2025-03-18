<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

if (file_exists(__DIR__ . "/../../.env")) {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv->load();
}

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'social';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die(json_encode(["error" => "Database Connection Failed", "message" => $e->getMessage()]));
        }
    }
}
?>
