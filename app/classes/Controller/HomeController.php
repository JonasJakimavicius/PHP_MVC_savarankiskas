<?php

namespace App\Controller;

use Core\Controller;
use Core\View;
use App\App;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @param
     * @throws \Exception
     */
    public function setPagePost()
    {
        if (!App::$session->isLoggedIn()) {
            header('Location: login');
        }

        $this->page['stylesheets'] = ['media/CSS/navbar.css'];
        $this->page['content'] = ['HomeForm'=>(new \App\Views\HomeForm())->render(),'cardsSection'=> (new \App\Views\ProductsTable())->render(),'UpdateForm'=>(new \App\Views\UpdateForm())->render()];
        $this->page['header'] = (new \App\Views\NavBar())->render();
        $this->page['footer'] = 'footeris';
        $this->page['scripts']['body_end']=['media/JS/main.js'];

    }

    /**
     * @param
     * @throws \Exception
     */
    public
    function setPageGet()
    {
        if (!App::$session->isLoggedIn()) {
            header('Location: login');
        }


        $this->page['stylesheets'] = ['media/CSS/navbar.css'];
        $this->page['content'] = ['HomeForm'=>(new \App\Views\HomeForm())->render(),'cardsSection'=> (new \App\Views\ProductsTable())->render(),'UpdateForm'=>(new \App\Views\UpdateForm())->render()];
        $this->page['header'] = (new \App\Views\NavBar())->render();
        $this->page['footer'] = 'footeris';
        $this->page['scripts']['body_end']=['media/JS/main.js'];
    }


    public function onRender()
    {
        return (new View($this->page))->render(ROOT . '/core/views/layout.tpl.php');

    }
}

;