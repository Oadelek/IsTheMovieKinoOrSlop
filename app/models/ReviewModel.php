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
}
