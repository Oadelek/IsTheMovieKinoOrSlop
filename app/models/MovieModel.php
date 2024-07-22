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
        // Search OMDB API directly
        $omdbData = $this->omdb->getMovieDetails($query);

        if (isset($omdbData['Title'])) {
            // Format OMDB results
            $movies = [
                [
                    'id' => null, 
                    'title' => $omdbData['Title'],
                    'year' => $omdbData['Year'],
                    'director' => $omdbData['Director'],
                    'plot' => $omdbData['Plot'],
                    'poster' => $omdbData['Poster'],
                    'omdb_data' => $omdbData
                ]
            ];
            return $movies;
        }

        // If no results from OMDB, search the local database
        $sql = "SELECT * FROM movies WHERE title LIKE :query";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $movies;
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
                    // Insert movie into the database
                    $sql = "INSERT INTO movies (imdb_id, title, year, director, plot, poster) 
                            VALUES (:imdb_id, :title, :year, :director, :plot, :poster)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':imdb_id', $omdbData['imdbID']);
                    $stmt->bindValue(':title', $omdbData['Title']);
                    $stmt->bindValue(':year', $omdbData['Year']);
                    $stmt->bindValue(':director', $omdbData['Director']);
                    $stmt->bindValue(':plot', $omdbData['Plot']);
                    $stmt->bindValue(':poster', $omdbData['Poster']);
                    $stmt->execute();

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
