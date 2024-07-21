<?php
class MovieModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    public function getMovies() {
        $sql = "SELECT * FROM movies";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovie($id) {
        $sql = "SELECT * FROM movies WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMovie($data) {
        $sql = "INSERT INTO movies (imdb_id, title, year, director, plot, poster) VALUES (:imdb_id, :title, :year, :director, :plot, :poster)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':imdb_id', $data['imdb_id']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':director', $data['director']);
        $stmt->bindParam(':plot', $data['plot']);
        $stmt->bindParam(':poster', $data['poster']);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
