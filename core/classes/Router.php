<?php


namespace Core;


class Router
{
    public static $routes;

    static function addRoute($url, $controller_name)
    {
        self::$routes[$url] = $controller_name;
    }

    public function getRouteController($url)
    {


        if (in_array($url, array_keys(self::$routes))) {
            $route = self::$routes[$url];
            $controller = new $route ();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller->setPageGet();
                return $controller->onRender();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->setPagePost();
                return $controller->onRender();
            }

        }
    }

}