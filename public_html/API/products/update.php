<?php

require '../../../bootloader.php';

$form = (new \App\Views\UpdateForm())->getData();

$filtered_input = get_form_input($form);
validate_form($filtered_input, $form);

function form_success($filtered_input, $form)
{
    $response = new \Core\Api\Response();
    $newProduct = new App\Products\Products($filtered_input);
    $newProduct->setId($_POST['id']);
    $status = \App\App::$prod_repository->update($newProduct);
    if ($status) {
        $response->addData($newProduct->getData());
    } else {
        $response->addError('Nepavyko atnaujinti produkto');
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