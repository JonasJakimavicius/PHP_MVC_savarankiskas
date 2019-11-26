<?php

namespace Core;

class Controller
{

    /**
     * Page data container
     * @var array
     */
    protected $page;

    public function __construct()
    {


        // Init Page Defaults
        $this->page = [
            'title' => 'Ar kažką išmokau?',
            'stylesheets' => [],
            'scripts' => [
                'head' => [],
                'body_start' => [],
                'body_end' => []
            ],
            'header' => false,
            'footer' => false,
            'content' => 'This is core controller!'
                . 'You need to extend this class in your App!',
        ];
    }

    public function onRender()
    {
        return (new View($this->page))->render(ROOT_DIR . $tpl_path);
    }

}
