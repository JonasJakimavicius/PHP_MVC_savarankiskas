<?php

namespace App\Products\Views;

class BaseForm extends \Core\Views\Form
{

    public function __construct($data = [])
    {
        $this->data = [
            'fields' => [
                'brand' => [
                    'label' => 'Gamintojas',
                    'type' => 'text',
                ],
                'model' => [
                    'label' => 'Modelis',
                    'type' => 'text',
                ],
                'year' => [
                    'label' => 'Metai',
                    'type' => 'text',
                ],
                'image' => [

                    'label' => 'Nuotrauka',
                    'type' => 'text',

                ],
                'fuel_type' => [
                    'label' => 'Kuras',
                    'type' => 'text',
                ],
                'doors' => [
                    'label' => 'Durų skaičius',
                    'type' => 'text',
                ],
            ],
            'buttons' => [
                'submit' => [
                    'title' => 'Submit',
                ],
            ]

        ];
    }

}
