<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if $url is not null and has at least one element
        if ($url !== null && !empty($url[1])) {
            $controllerName = ucwords($url[1]) . 'Controller';
            if(file_exists('app/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[1]);
            }
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if(isset($url[2])) {
            if(method_exists($this->controller, $url[2])) {
                $this->method = $url[2];
                unset($url[2]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        //trims the trailing forward slash (rtrim), sanitizes URL, explode it by forward slash to get elements
        $url = explode('/', filter_var(rtrim($u, '/'), FILTER_SANITIZE_URL));
        unset($url[0]);
        return $url;
    }
}