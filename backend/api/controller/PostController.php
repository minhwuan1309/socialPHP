<?php
require_once '../config/dbConfig.php';
require_once '../models/Post.php';
header('Content-Type: application/json');


class PostController {
    private $conn;
    private $post;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->post = new Post($this->conn);
    }

    public function getPosts() {
        $posts = $this->post->getAllPosts();
    
        if (empty($posts)) {
            echo json_encode(["message" => "Không có bài viết nào."]);
        } else {
            echo json_encode($posts);
        }
    }

    public function createPost($data) {
        if (!isset($data['user_id'], $data['content'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Thiếu dữ liệu đầu vào!']);
            return;
        }
        
        $postId = $this->post->createPost($data['user_id'], $data['content'], $data['image'] ?? null);
        if ($postId) {
            http_response_code(201);
            echo json_encode(['message' => 'Bài viết đã được tạo!', 'post_id' => $postId]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Tạo bài viết thất bại!']);
        }
    }

    public function updatePost($id, $data) {
        if (!isset($data['user_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Thiếu user_id!']);
            return;
        }

        $updated = $this->post->updatePost($id, $data['user_id'], $data['content'] ?? null, $data['image'] ?? null);
        if ($updated) {
            echo json_encode(['message' => 'Bài viết đã được cập nhật!']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Bạn không có quyền chỉnh sửa bài viết này!']);
        }
    }

    public function deletePost($id, $user_id) {
        $deleted = $this->post->deletePost($id, $user_id);
        if ($deleted) {
            echo json_encode(['message' => 'Bài viết đã được xoá!']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Bạn không có quyền xoá bài viết này!']);
        }
    }
}
?>
