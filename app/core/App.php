<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if $url is not null and has at least one element
        if ($url !== null && !empty($url[0])) {
            $controllerName = ucwords($url[0]) . 'Controller';
            if(file_exists('app/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Add GET parameters to the params array
        $this->params = array_merge($this->params, $_GET);

       // error_log("Controller: " . $this->controller . ", Method: " . $this->method);
        error_log("Params: " . print_r($this->params, true));

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        // Parse the URL and remove query string
        $parsedUrl = parse_url($u);
        $path = $parsedUrl['path'];

        // Trim trailing slash, sanitize URL, explode by forward slash to get elements
        $url = explode('/', filter_var(rtrim($path, '/'), FILTER_SANITIZE_URL));
        array_shift($url); // Remove the first empty element
        return $url;
    }
}