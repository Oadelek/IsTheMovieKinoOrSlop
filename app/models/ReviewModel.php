<?php
class ReviewModel {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function addReview($data) {
        $sql = "INSERT INTO reviews (user_id, movie_id, rating, content, ai_generated) VALUES (:user_id, :movie_id, :rating, :content, :ai_generated)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':movie_id', $data['movie_id']);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':ai_generated', $data['ai_generated']);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getReviews($movie_id) {
        $sql = "SELECT * FROM reviews WHERE movie_id = :movie_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':movie_id', $movie_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserReviews($userId) {
        $sql = "SELECT r.*, m.title as movie_title 
                FROM reviews r 
                JOIN movies m ON r.movie_id = m.id 
                WHERE r.user_id = :user_id 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewById($reviewId) {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReview($reviewId, $data) {
        $sql = "UPDATE reviews 
                SET rating = :rating, content = :content, ai_generated = :ai_generated 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $data['rating']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':ai_generated', $data['ai_generated'], PDO::PARAM_BOOL);
        return $stmt->execute();
    }
}


