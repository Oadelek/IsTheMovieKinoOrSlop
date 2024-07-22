<?php
class MovieModel {
    private $db;

    public function __construct() {
        $this->db = db_connect();
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

    public function searchMovies($query) {
        // Search in the local database
        $sql = "SELECT * FROM movies WHERE title LIKE :query";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Optional: fetch additional movie details from OMDB API
        foreach ($movies as &$movie) {
            $omdbData = $this->omdb->getMovieDetails($movie['title']);
            if (isset($omdbData['Title'])) {
                $movie['omdb_data'] = $omdbData;
            }
        }

        return $movies;
    }
}
