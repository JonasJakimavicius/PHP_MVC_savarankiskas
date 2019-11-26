<?php

require '../../../bootloader.php';


$form = (new \App\Views\HomeForm())->getData();
$filtered_input = get_form_input($form);
validate_form($filtered_input, $form);


function form_success($filtered_input, $form)
{
    $response = new \Core\Api\Response();
    $product = new App\Products\Products($filtered_input);
    $new_product_id = \App\App::$prod_repository->insert($product);
    $new_product = \App\App::$prod_repository->load($product->getData())[0];


    if ($new_product_id) {

        $response->setData($new_product->getData());
    } else {
        $response->addError('Toks produktas jau yra');
    }
    $response->print();
}

function form_fail($filtered_input, $form)
{
    $response = new \Core\Api\Response();
    $errors = [];
    foreach ($form['fields'] as $field_id => $field) {
        if (isset($field['error'])) {
            $response->addError($field['error'], $field_id);
        }
    }
    $response->print();

}


