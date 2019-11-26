<?php

namespace App;

use Core\Database\Connection;
use Core\Database\Schema;
use Core\User\Repository;

class App {

    /** @var \Core\FileDB **/
    public static $db;
    
    /** @var \Core\Session **/
    public static $session;

    public static $connection;

    public static $schema;

    public static $repository;

    public function __construct() {
        self::$db = new \Core\FileDB(DB_FILE);
        self::$db->load();

        self::$connection=new \Core\Database\Conection(DNS);
        self::$schema=new Schema(MYDB);
        self::$repository= new Repository();

        self::$session = new \Core\Session();
    }

    public function __destruct() {
        self::$db->save();
    }

}
