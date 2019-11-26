<?php

namespace App;

use Core\Users\Session;
use Core\Database\Connection;
use Core\Database\Schema;
use Core\Router;
use Core\Users\Repository;


class App
{

    /** @var \Core\Session * */
    public static $session;
    public static $connection;
    public static $schema;
    public static $router;
    public static $repository;
    public static $prod_repository;

    public function __construct()
    {

        self::$connection = new \Core\Database\Conection(DNS);
        self::$schema = new Schema(MYDB);
        self::$repository = new Repository();
        self::$session = new Session(self::$repository);
        self::$router = new Router();
        self::$prod_repository = new \App\Products\Repository();
    }

    public static function run()
    {
        print $controller = self::$router->getRouteController($_SERVER['REQUEST_URI']);

    }


    public function __destruct()
    {

    }

}
