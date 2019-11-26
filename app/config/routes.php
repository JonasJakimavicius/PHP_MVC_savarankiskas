<?php


\Core\Router::addRoute('/about', 'App\controller\AboutController');
\Core\Router::addRoute('/index', 'App\Controller\HomeController');
\Core\Router::addRoute('/Index', 'App\Controller\HomeController');
\Core\Router::addRoute('/', 'App\Controller\HomeController');

\Core\Router::addRoute('/logout', 'App\controller\LogoutController');
\Core\Router::addRoute('/register', 'App\controller\RegisterController');


if (\App\App::$session->isLoggedIn()) {
    \Core\Router::addRoute('/login', 'App\controller\HomeController');
} else {
    \Core\Router::addRoute('/login', 'App\controller\LoginController');

}
