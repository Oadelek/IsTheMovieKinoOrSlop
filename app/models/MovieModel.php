<?php
class MovieModel {
    private $db;
    private $omdb;

    public function __construct() {
        $this->db = db_connect();
        $this->omdb = new OMDB();
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

    public function getGenres() {
        $sql = "SELECT * FROM genres";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMoviesByGenre($genreId) {
        $sql = "
            SELECT m.* 
            FROM movies m
            JOIN movie_genres mg ON m.id = mg.movie_id
            WHERE mg.genre_id = :genre_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':genre_id', $genreId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $movies = [];

        // Search in the local database
        $sql = "SELECT * FROM movies WHERE title LIKE :query";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($movies)) {
            // Search in the OMDB API
            $omdbData = $this->omdb->getMovieDetails($query);

            if (isset($omdbData['Title'])) {
                
                // Check if movie already exists
                $sql = "SELECT * FROM movies WHERE imdb_id = :imdb_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':imdb_id', $omdbData['imdbID']);
                $stmt->execute();
                $existingMovie = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$existingMovie) {
                    // Prepare the year data
                    $year = isset($omdbData['Year']) ? intval(substr($omdbData['Year'], 0, 4)) : null;

                    // Insert movie into the database
                    $sql = "INSERT INTO movies (imdb_id, title, year, director, plot, poster) 
                            VALUES (:imdb_id, :title, :year, :director, :plot, :poster)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':imdb_id', $omdbData['imdbID']);
                    $stmt->bindValue(':title', substr($omdbData['Title'], 0, 255));
                    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
                    $stmt->bindValue(':director', substr($omdbData['Director'], 0, 100));
                    $stmt->bindValue(':plot', $omdbData['Plot']);
                    $stmt->bindValue(':poster', substr($omdbData['Poster'], 0, 255));
                    
                    try {
                        $stmt->execute();
                    } catch (PDOException $e) {
                        error_log("Database error: " . $e->getMessage());
                        error_log("OMDB Data: " . print_r($omdbData, true));
                        return [];
                    }

                    // Retrieve inserted movie ID
                    $movieId = $this->db->lastInsertId();

                    // Add actors if not already in the database
                    $actors = explode(', ', $omdbData['Actors']);
                    foreach ($actors as $actor) {
                        $sql = "SELECT id FROM actors WHERE name = :name";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindValue(':name', $actor);
                        $stmt->execute();
                        $actorId = $stmt->fetchColumn();

                        if (!$actorId) {
                            $sql = "INSERT INTO actors (name) VALUES (:name)";
                            $stmt = $this->db->prepare($sql);
                            $stmt->bindValue(':name', $actor);
                            $stmt->execute();
                            $actorId = $this->db->lastInsertId();
                        }

                        // Link actor to the movie
                        $sql = "INSERT INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindValue(':movie_id', $movieId);
                        $stmt->bindValue(':actor_id', $actorId);
                        $stmt->execute();
                    }

                    // Add genres if not already in the database
                    $genres = explode(', ', $omdbData['Genre']);
                    foreach ($genres as $genre) {
                        $sql = "SELECT id FROM genres WHERE name = :name";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindValue(':name', $genre);
                        $stmt->execute();
                        $genreId = $stmt->fetchColumn();

                        if (!$genreId) {
                            $sql = "INSERT INTO genres (name) VALUES (:name)";
                            $stmt = $this->db->prepare($sql);
                            $stmt->bindValue(':name', $genre);
                            $stmt->execute();
                            $genreId = $this->db->lastInsertId();
                        }

                        // Link genre to the movie
                        $sql = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (:movie_id, :genre_id)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindValue(':movie_id', $movieId);
                        $stmt->bindValue(':genre_id', $genreId);
                        $stmt->execute();
                    }
                }

                // Retrieve movie details again after insertion
                $movies = $this->searchMovies($query); // Re-run search to include newly added movie
            }
        }

        return $movies;
    }


    
}
