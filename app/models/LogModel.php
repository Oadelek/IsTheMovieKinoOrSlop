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

    public function getLog($user_id) {
        $sql = "SELECT * FROM user_viewing_history WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
