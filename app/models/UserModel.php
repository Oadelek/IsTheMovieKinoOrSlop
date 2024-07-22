<?php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function register($data) {
        if ($this->getUserByUsername($data['username'])) {
            error_log("Username already exists");
            return false;
        }

        if (!$this->validatePassword($data['password'])) {
            error_log("Password does not meet the minimum security requirements");
            return false ;
        }

        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_DEFAULT));

        return $stmt->execute();
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            error_log("Invalid login attempt for username: " . $username);
            return false;
        }
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserIdByUsername($username) {
        $sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    public function getLoginCounts() {
        $sql = "SELECT username, COUNT(*) as login_count FROM login_attempts WHERE attempt = 'good' GROUP BY username ORDER BY login_count DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validatePassword($password) {
        $min_length = 8;

        if (strlen($password) < $min_length) return false;
        if (!preg_match('/[A-Z]/', $password)) return false;
        if (!preg_match('/[a-z]/', $password)) return false;
        if (!preg_match('/\d/', $password)) return false;
        if (!preg_match('/[^a-zA-Z\d]/', $password)) return false;

        return true;
    }

    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getWatchlist($userId) {
        $sql = "SELECT m.* FROM movies m 
                JOIN user_watchlist w ON m.id = w.movie_id 
                WHERE w.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleWatchlist($userId, $movieId) {
        $sql = "SELECT * FROM user_watchlist WHERE user_id = :user_id AND movie_id = :movie_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "DELETE FROM user_watchlist WHERE user_id = :user_id AND movie_id = :movie_id";
            $inWatchlist = false;
        } else {
            $sql = "INSERT INTO user_watchlist (user_id, movie_id) VALUES (:user_id, :movie_id)";
            $inWatchlist = true;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $success = $stmt->execute();

        return ['success' => $success, 'inWatchlist' => $inWatchlist];
    }

    public function getAISettings($userId) {
        $sql = "SELECT * FROM ai_review_settings WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAISettings($userId, $settings) {
        $sql = "INSERT INTO ai_review_settings (user_id, word_count, humor_level, critic_level, style) 
                VALUES (:user_id, :word_count, :humor_level, :critic_level, :style)
                ON DUPLICATE KEY UPDATE 
                word_count = :word_count, humor_level = :humor_level, 
                critic_level = :critic_level, style = :style";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':word_count', $settings['word_count'], PDO::PARAM_INT);
        $stmt->bindParam(':humor_level', $settings['humor_level'], PDO::PARAM_INT);
        $stmt->bindParam(':critic_level', $settings['critic_level'], PDO::PARAM_INT);
        $stmt->bindParam(':style', $settings['style'], PDO::PARAM_STR);
        return $stmt->execute();
    }
}