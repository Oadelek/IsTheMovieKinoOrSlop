<?php
class LogModel {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function addLog($data) {
        $sql = "INSERT INTO user_viewing_history (user_id, movie_id) VALUES (:user_id, :movie_id)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':movie_id', $data['movie_id']);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getLog($userId) {
        $sql = "SELECT uvh.*, m.title, m.year, m.poster 
                FROM user_viewing_history uvh
                JOIN movies m ON uvh.movie_id = m.id
                WHERE uvh.user_id = :user_id
                ORDER BY uvh.viewed_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
