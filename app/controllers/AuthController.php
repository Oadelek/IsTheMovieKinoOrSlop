<?php

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    public function login() {
        $data = [
            'username' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['username'] = trim($_POST['username']);
            $data['password'] = trim($_POST['password']);

            $user = $this->userModel->login($data['username'], $data['password']);

            if ($user) {
                // Create Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('location: /');
            } else {
                $data['error'] = 'Invalid username or password';
            }
        }

        $this->view('layouts/PublicHeaderView', $data);
        $this->view('auth/LoginView', $data);
        $this->view('layouts/FooterView');
    }

    public function register() {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['username'] = trim($_POST['username']);
            $data['email'] = trim($_POST['email']);
            $data['password'] = trim($_POST['password']);

            if ($this->userModel->register($data)) {
                header('location: /auth/login');
            } else {
                $data['error'] = 'Error registering user. Username might already exist or password does not meet the requirements.';
            }
        }

        $this->view('layouts/PublicHeaderView', $data);
        $this->view('auth/RegisterView', $data);
        $this->view('layouts/FooterView');
    }

    public function logout() {
        session_unset();
        session_destroy();
        // Clear the session cookie (if any)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        header('location: /');
        exit();
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('location: /auth/login');
            exit();
        }

        $userModel = $this->model('UserModel');
        $logModel = $this->model('LogModel');
        $reviewModel = $this->model('ReviewModel');

        $user = $userModel->getUserById($_SESSION['user_id']);
        $viewingHistory = $logModel->getLog($_SESSION['user_id']);
        $watchlist = $userModel->getWatchlist($_SESSION['user_id']);
        $reviews = $reviewModel->getUserReviews($_SESSION['user_id']);
        $aiSettings = $userModel->getAISettings($_SESSION['user_id']);

        $data = [
            'user' => $user,
            'viewingHistory' => $viewingHistory,
            'watchlist' => $watchlist,
            'reviews' => $reviews,
            'aiSettings' => $aiSettings
        ];

        $this->view('layouts/PrivateHeaderView');
        $this->view('user/ProfileView', $data);
        $this->view('layouts/FooterView');
    }
}
