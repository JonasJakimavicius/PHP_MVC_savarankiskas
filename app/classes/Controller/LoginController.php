<?php

namespace App\Controller;

use App\App;
use App\Views\LoginForm;
use Core\Controller;
use Core\Users\User;
use Core\View;

class LoginController extends Controller
{
    public $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new LoginForm();


    }

    /**
     * @param
     * @throws \Exception
     */
    public function setPagePost()
    {


        $this->form->getFormAction();
        if ($this->form->validateform()) {
            if (!empty(App::$repository->load($this->form->filtered_input))) {

                App::$session->login(
                    $this->form->filtered_input['email'],
                    $this->form->filtered_input['password']
                );

                header('Location: /');
            } else {
                $this->page['stylesheets'] = ['media/CSS/navbar.css'];
                $this->page['content'] = ['login' => 'Nepavyko prisilogint'];
                $this->page['header'] = (new \App\Views\NavBar())->render();
                $this->page['footer'] = 'footeris';

            }
        }
    }

    /**
     * @param
     * @throws \Exception
     */
    public
    function setPageGet()
    {

        if (App::$session->isLoggedIn()) {
            $login = (new LoginController())->setPageGet();
        }

        $this->page['stylesheets'] = ['media/CSS/navbar.css'];
        $this->page['content'] = [
            'login' => (new \App\Views\LoginForm())->render()
        ];
        $this->page['header'] = (new \App\Views\NavBar())->render();
        $this->page['footer'] = 'footeris';
    }


    public
    function onRender()
    {
        return (new View($this->page))->render(ROOT . '/core/views/layout.tpl.php');

    }
}

;