<?php

require '../../../bootloader.php';

$response = new \Core\Api\Response();
$products = \App\App::$prod_repository->load($_POST);


if($products) {
    foreach ($products as $product) {

        $response->addData($product->getData());
    }
}else{
    $response->addError('Could not pull data from database!');
}

$response->print();