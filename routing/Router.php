<?php

class Router {

    private static $routes = array();

    public static function getRoutes() {
        return self::$routes;
    }

    public static function log() {
        print_r(self::$routes);
    }

    public static function push($route, $controllerAction, $params=array(), $method="GET", $appendAction="", $prependAction="") {
        $controllerName = $route;
        $action = explode('@', $controllerAction)[1];
        $controller = explode('@', $controllerAction)[0];

        $regex = $route;

        $prepend = $prependAction ? "\/$prependAction" : "";

        $regex .= $prepend;

        foreach ($params as $param) {
            $regex .= "\/[A-Za-z0-9_-]{1,}";
        }

        $regex .= $appendAction !== ""? "[\/]$appendAction" : "" ;
        $regex .= "[\/]{0,1}";

        $regex = "/^$regex$/";

        self::$routes[$controllerName][$action] = array(
            "pattern" => $regex,
            "params" => $params,
            "action" => $action,
            "controllerClass" => $controller,
            "method" => $method,
            "actionTitle" => $appendAction
        );
    }
    

    public static function pushController($url, $controller, $customTitles=array(), $customParams=array("id")) {
        self::push($url, "$controller@index", array());

        $key = array_key_exists("create", $customTitles)? $customTitles['create'] : "create";
        self::push($url, "$controller@create", array(), "GET", $key);
        
        self::push($url, "$controller@show", $customParams);
        self::push($url, "$controller@store", array(), "POST", $key);

        $key = array_key_exists("edit", $customTitles)? $customTitles['edit'] : "edit";
        self::push($url, "$controller@load", $customParams, "GET", $key);

        $key = array_key_exists("edit", $customTitles)? $customTitles['edit'] : "edit";
        self::push($url, "$controller@edit", $customParams, "POST", $key);

        $key = array_key_exists("destroy", $customTitles)? $customTitles['destroy'] : "destroy";
        self::push($url, "$controller@destroy", [], "POST", $key);
    }

    public static function run() {
        $controllerName = URLUtils::controllerName();

        if (!array_key_exists($controllerName, self::$routes)) {
            echo "Neexistuje";
            $controller = new ErrorController();
            $controller->run("error");
            return;
        }

        $availableRoutes = self::$routes[$controllerName];
        $currentURI = URLUtils::localhostSpecificUrl();
        $currentURI = join("/", $currentURI);

        $match = false;
        foreach($availableRoutes as $key => $route) {
       
            $method = URLUtils::getMethod() == $route["method"];
            $regMatch = preg_match($route['pattern'], $currentURI) == true;

            if ($regMatch && $method) {
                $controller = new $route["controllerClass"];
                $action = $route["action"];

                $params = self::extractParamsFromURL($route);
                $controller->setParams($params);
                $controller->run($action);
                $match = true;

                break;
            }
        }

        if (!$match) {
            $controller = new ErrorController();
            $controller->run("error");
        }
    }

    public static function extractParamsFromURL($route) {
        $url = URLUtils::localhostSpecificURL();
        $actionAtTheEndOfUrl = $route["actionTitle"] !== ""? true : false; 
        
        $len = $actionAtTheEndOfUrl? count($url) - 1 : count($url);

        # odebrat kontroller
        # odebrat akci, pokud je na konci
        $url = array_slice($url, 1, $len - 1);
        
        $keys = $route['params'];
        
        # ujistíme se, aby byly opravdu stejné délky
        $vals = array_slice($url, 0, count($keys));
        
        $params = array_combine($keys, $vals);

        return $params;
    }

    public static function api($url, $api) {

        self::push("api", "$api@get", $params=array(), $method="GET", "", $url);
        self::push("api", "$api@getOne", $params=array("id"), $method="GET", "", $url);
        self::push("api", "$api@post", $params=array(), $method="POST", "", $url);
        self::push("api", "$api@put", $params=array("id"), $method="PUT", "", $url);
        self::push("api", "$api@delete", $params=array("id"), $method="DELETE", "", $url);
    }

}