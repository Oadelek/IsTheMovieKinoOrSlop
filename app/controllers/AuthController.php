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
        header('location: /');
    }
}
