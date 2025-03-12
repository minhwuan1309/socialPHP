<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Đường dẫn đến autoload.php
use Dotenv\Dotenv;

// Load file .env
$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Kiểm tra nếu biến môi trường được load đúng
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'social';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
    }
}
?>
