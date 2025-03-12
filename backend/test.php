<?php
require_once __DIR__ . '/api/config/dbConfig.php';

$database = new Database();
$conn = $database->connect();
if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed!";
}
?>
