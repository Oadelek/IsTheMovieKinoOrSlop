<?php
class ReviewController extends Controller {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = $this->model('ReviewModel');
    }

    public function create($movie_id) {
        if (!isset($_SESSION['user_id'])) {
            header('location: /auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            $data['content'] = $gemini->generateReview($data['rating'], $filters);

            if ($this->reviewModel->addReview($data)) {
                header('location: /movies/details/' . $movie_id);
            } else {
                $data['error'] = 'Error creating review';
                $this->view('layouts/PrivateHeaderView');
                $this->view('review/CreateReviewView', $data);
                $this->view('layouts/FooterView');
            }
        } else {
            $data = [
                'movie_id' => $movie_id
            ];
            $this->view('layouts/PrivateHeaderView');
            $this->view('review/CreateReviewView', $data);
            $this->view('layouts/FooterView');
        }
    }
}