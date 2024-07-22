<?php
class HomeController extends Controller {
    private $movieModel;

    public function __construct() {
        $this->movieModel = $this->model('MovieModel');
    }

    public function index() {
        $genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Romance']; 
        $data = [];

        foreach ($genres as $genre) {
            $data[$genre] = $this->movieModel->getMoviesByGenre($genre, 10); // Limit to 10 movies per genre
        }

        $this->view('layouts/PublicHeaderView');
        $this->view('movies/HomeView', $data); // Pass genre data to HomeView
        $this->view('layouts/FooterView');
    }
}
