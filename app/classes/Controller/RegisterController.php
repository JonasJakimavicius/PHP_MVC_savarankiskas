<?php

namespace App\Controller;

use App\App;
use App\Views\RegisterForm;
use Core\Controller;
use Core\Forms\Form;
use Core\Users\User;
use Core\View;

class RegisterController extends Controller
{

    public $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new RegisterForm();

    }

    /**
     * @param
     * @throws \Exception
     */
    public function setPagePost()
    {
        $this->form->getFormAction();

        if ($this->form->validateform()) {
            $user=new User($this->form->filtered_input);
            App::$repository->insert($user);


            $this->page['stylesheets'] = ['media/CSS/navbar.css'];
            $this->page['content'] =['register'=>'Sveikinu prisireginus'];
            $this->page['header'] = (new \App\Views\NavBar())->render();
            $this->page['footer'] = 'footeris';
        }else{
            $this->page['stylesheets'] = ['media/CSS/navbar.css'];
            $this->page['content'] =['register'=>'Nepavyko prisiregistruoti'];
            $this->page['header'] = (new \App\Views\NavBar())->render();
            $this->page['footer'] = 'footeris';

        }

    }

    /**
     * @param
     * @throws \Exception
     */
    public function setPageGet()
    {
        $this->page['stylesheets'] = ['media/CSS/navbar.css'];
        $this->page['content'] = ['registerForm'=>(new \App\Views\RegisterForm())->render()];
        $this->page['header'] = (new \App\Views\NavBar())->render();
        $this->page['footer'] = 'footeris';
    }


    public function onRender()
    {
        return (new View($this->page))->render(ROOT . '/core/views/layout.tpl.php');

    }
}

;