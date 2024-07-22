<?php
class ReviewController extends Controller {
    private $reviewModel;
    private $movieModel;

    public function __construct() {
        $this->reviewModel = $this->model('ReviewModel');
        $this->movieModel = $this->model('MovieModel');
    }

    public function create($movie_id) {
        if (!isset($_SESSION['user_id'])) {
            header('location: /auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $movie = $this->movieModel->getMovie($movie_id); // Fetch the movie details
            $movieTitle = $movie['title']; // Get the movie title
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'movie_id' => $movie_id,
                'rating' => trim($_POST['rating']),
                'content' => '',
                'ai_generated' => true
            ];

            $gemini = new Gemini();
            $filters = [
                'word_count' => $_POST['word_count'],
                'humor_level' => $_POST['humor_level'],
                'critic_level' => $_POST['critic_level'],
                'style' => $_POST['style']
            ];

            $data['content'] = $gemini->generateReview($data['rating'], $filters, $movieTitle);

            if ($this->reviewModel->addReview($data)) {
                header('location: /movies/details/' . $movie_id);
                exit();
            } else {
                $data['error'] = 'Error creating review';
                $this->view('layouts/PrivateHeaderView');
                $this->view('review/CreateReviewView', $data);
                $this->view('layouts/FooterView');
            }
        } else {
            $data = [
                'movie_id' => $movie_id,
                'movie' => $this->movieModel->getMovie($movie_id) // Load movie details for view
            ];
            $this->view('layouts/PrivateHeaderView');
            $this->view('review/CreateReviewView', $data);
            $this->view('layouts/FooterView');
        }
    }
}