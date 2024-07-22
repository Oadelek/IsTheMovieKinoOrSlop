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

    public function search() {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $movies = $this->movieModel->searchMovies($query);
            $data = [
                'movies' => $movies
            ];
            $this->view('layouts/PublicHeaderView');
            $this->view('movies/SearchView', $data);
            $this->view('layouts/FooterView');
        } else {
            $this->index(); // Redirect to the search page if no query
        }
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