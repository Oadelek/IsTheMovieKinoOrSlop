<?php


// Start session
error_reporting(0);
ini_set('session.gc_maxlifetime', 28800);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
$sessionCookieExpireTime = 28800; // 8hrs
session_set_cookie_params($sessionCookieExpireTime);
session_start();


require_once 'app/helper/Database.php';
require_once 'app/helper/Gemini.php';
require_once 'app/helper/OMDB.php';

// Load core libraries
require_once 'app/core/App.php';
require_once 'app/core/Config.php';
require_once 'app/core/Controller.php';

// Load models
require_once 'app/models/UserModel.php';
require_once 'app/models/MovieModel.php';
require_once 'app/models/ReviewModel.php';
require_once 'app/models/LogModel.php';