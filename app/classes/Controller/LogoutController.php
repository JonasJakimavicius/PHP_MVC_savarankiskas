<?php

namespace App\Controller;

use Core\Controller;
use Core\View;

class LogoutController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        \App\App::$session->logout();

        header('Location: /');


    }


    public function onRender()
    {
        return (new View($this->page))->render(ROOT . '/core/views/layout.tpl.php');

    }
}

;