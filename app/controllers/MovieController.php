<?php
class MovieController extends Controller {
    private $movieModel;

    public function __construct() {
        $this->movieModel = $this->model('MovieModel');
    }

    public function index() {
        $this->view('layouts/PublicHeaderView');
        $this->view('movies/SearchView');
        $this->view('layouts/FooterView');
    }

    public function search($query = null) {
        error_log("Entering search method"); // Debug output
        if ($query === null && isset($_GET['query'])) {
            $query = $_GET['query'];
        }

        if ($query) {
            error_log("Query found: " . $query); // Debug output
            $movies = $this->movieModel->searchMovies($query);
            $data = [
                'movies' => $movies,
                'query' => $query
            ];
        } else {
            error_log("No query found"); // Debug output
            $data = [
                'query' => ''
            ];
        }

        $this->view('layouts/PublicHeaderView');
        $this->view('movies/SearchView', $data);
        $this->view('layouts/FooterView');
    }

    public function details($id) {
        $movie = $this->movieModel->getMovie($id);
        $reviews = $this->model('ReviewModel')->getReviews($id);
        $data = [
            'movie' => $movie,
            'reviews' => $reviews
        ];
        $this->view('layouts/PublicHeaderView');
        $this->view('movies/DetailsView', $data);
        $this->view('layouts/FooterView');
    }
}