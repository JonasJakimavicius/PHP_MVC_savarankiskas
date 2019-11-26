<?php

require '../../../bootloader.php';


$form = (new \App\Products\Views\ApiForm())->getData();
$filtered_input = get_form_input($form);
validate_form($filtered_input, $form);


function form_success($filtered_input, $form)
{

    $repository = new \App\Products\Repository();
    $response = new \Core\Api\Response();
    $car = new App\Products\Car($filtered_input);
    $car->setId($_POST['card-id']);

    $id = $repository->update($car);
    if ($id !== false) {

        $response->setData($car->getData());

    } else {
        $response->addError('Update failed!');
    }

    $response->print();
}

function form_fail($filtered_input, $form)
{
    $response = new \Core\Api\Response();

    foreach ($form['fields'] as $field_id => $field) {
        if (isset($field['error'])) {
            $response->addError($field['error'], $field_id);
        }
    }

    $response->print();
}


$status = $repository->insert($car);

if ($status) {
    $response->setData($car);
} else {
    $response->addError('Update to database failed!');
}

$response->print();