<?php
require '../../../bootloader.php';

$repository = new \App\Products\Repository();
$response = new \Core\Api\Response();

if (empty($_POST)) {

    $allCars = $repository->loadAll();
    foreach ($allCars as $id => $car) {
        $response->addData($car->getData());
    }
} else {
    $car = $repository->load('id', $_POST['card-id']);
    $response->addData($car->getData());
}
$response->print();