<?php
class Post {
    private $conn;
    private $table = "posts";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ Lấy danh sách bài viết
    public function getAllPosts() {
        $query = "SELECT posts.*, users.name AS author_name, users.avatar AS author_avatar 
                  FROM " . $this->table . " 
                  JOIN users ON posts.user_id = users.id 
                  ORDER BY posts.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Tạo bài viết
    public function createPost($user_id, $content, $image = null) {
        $query = "INSERT INTO " . $this->table . " (user_id, content, image) VALUES (:user_id, :content, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    // ✅ Cập nhật bài viết
    public function updatePost($id, $user_id, $content, $image) {
        $query = "SELECT user_id FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post || $post['user_id'] !== $user_id) {
            return false;
        }

        $query = "UPDATE " . $this->table . " SET content = :content, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }

    // ✅ Xoá bài viết
    public function deletePost($id, $user_id) {
        $query = "SELECT user_id FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post || $post['user_id'] !== $user_id) {
            return false;
        }

        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>