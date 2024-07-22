<?php
class MovieController extends Controller {
    private $movieModel;

    public function __construct() {
        $this->movieModel = $this->model('MovieModel');
    }

    public function search() {
        $this->view('layouts/PublicHeaderView');
        $this->view('movies/SearchView');
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