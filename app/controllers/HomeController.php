<?php
class HomeController extends Controller {
    private $movieModel;

    public function __construct() {
        $this->movieModel = $this->model('MovieModel');
    }

    public function index() {
        // Get genres and movies
        $genres = $this->movieModel->getGenres();
        $data = [];
        foreach ($genres as $genre) {
            $data['genres'][$genre['name']] = $this->movieModel->getMoviesByGenre($genre['id']);
        }

        // Render the views
        $this->view('layouts/PublicHeaderView');
        $this->view('movies/HomeView', $data);
        $this->view('layouts/FooterView');
    }
}