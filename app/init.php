<?php
require_once 'Database.php';
require_once 'Gemini.php';
require_once 'OMDB.php';

// Load core libraries
require_once 'app/core/App.php';
require_once 'app/core/Config.php';
require_once 'app/core/Controller.php';

// Load models
require_once 'app/models/UserModel.php';
require_once 'app/models/MovieModel.php';
require_once 'app/models/ReviewModel.php';
require_once 'app/models/LogModel.php';

// Start session
session_start();