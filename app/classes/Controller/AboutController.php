<?php

namespace App\Controller;


use Core\Controller;
use Core\View;

class AboutController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->page['stylesheets'] = ['media/CSS/navbar.css'];
        $this->page['content'] = ['„Kregždutės, kregždutės na**i!“'];
        $this->page['header'] = (new \App\Views\NavBar(["home", "login"]))->render();
        $this->page['footer'] = 'footeris';

    }


    public function onRender()
    {
        return (new View($this->page))->render(ROOT . '/core/views/layout.tpl.php');

    }
}

;