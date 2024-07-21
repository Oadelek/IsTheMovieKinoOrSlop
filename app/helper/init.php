<?php
error_reporting(0);
ini_set('session.gc_maxlifetime', 28800);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
$sessionCookieExpireTime = 28800; // 8hrs
session_set_cookie_params($sessionCookieExpireTime);
session_start();

require_once 'Database.php';
require_once 'Gemini.php';
require_once 'OMDB.php';
require_once '../core/App.php';
require_once '../core/Config.php';
require_once '../core/Controller.php';

spl_autoload_register(function($class) {
    if (file_exists('../models/' . $class . '.php')) {
        require_once '../models/' . $class . '.php';
    }
});
