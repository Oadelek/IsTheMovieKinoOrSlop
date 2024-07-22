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

        if ($this->reviewModel->hasUserReviewed($_SESSION['user_id'], $movie_id)) {
            // Set a session variable to indicate that the user has already reviewed the movie
            $_SESSION['already_reviewed'] = true;
            header('location: /movie/details/' . $movie_id . '#reviews');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'movie_id' => $movie_id,
                'rating' => trim($_POST['rating']),
                'content' => '',
                'ai_generated' => isset($_POST['ai_generated']) ? tru : false
            ];

            $gemini = new Gemini();
            $filters = [
                'word_count' => $_POST['word_count'],
                'humor_level' => $_POST['humor_level'],
                'critic_level' => $_POST['critic_level'],
                'style' => $_POST['style']
            ];

            $movie = $this->movieModel->getMovie($movie_id);
            $movieTitle = $movie['title'];

            $data['content'] = $gemini->generateReview($data['rating'], $movieTitle, $filters);

            // Instead of saving directly, pass to a preview page
            $this->view('layouts/PrivateHeaderView');
            $this->view('review/PreviewReviewView', $data);
            $this->view('layouts/FooterView');
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

    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'movie_id' => $_POST['movie_id'],
                'rating' => $_POST['rating'],
                'content' => $_POST['content'],
                'ai_generated' => isset($_POST['ai_generated']) ? 1 : 0
            ];

            if ($this->reviewModel->addReview($data)) {
                header('location: /movie/details/' . $data['movie_id'] . '#reviews');
            } else {
                // error handling
            }
        }
    }
}